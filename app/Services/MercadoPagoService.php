<?php

namespace App\Services;

use App\Events\CreateOrderEvent;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class MercadoPagoService
{
    private const API_URL = 'https://api.mercadopago.com';

    /**
     * @return array<string, mixed>
     */
    public function createPreference(Order $order): array
    {
        $items = collect($order->items ?? [])->map(fn (array $item): array => [
            'id' => (string) ($item['id'] ?? ''),
            'title' => (string) ($item['title'] ?? 'Producto'),
            'quantity' => (int) ($item['quantity'] ?? 0),
            'unit_price' => (float) ($item['unit_price'] ?? 0),
            'currency_id' => config('services.mercadopago.currency', 'COP'),
        ])->values()->all();

        if ($items === []) {
            throw new RuntimeException('La orden no tiene productos para pagar.');
        }

        // MP rejects auto_return when back_urls.success is not a public HTTPS URL (e.g. localhost).
        $publicAppUrl = str_starts_with(config('app.url'), 'https://')
            && ! in_array(parse_url(config('app.url'), PHP_URL_HOST), ['localhost', '127.0.0.1']);

        $response = Http::withToken(config('services.mercadopago.token'))
            ->acceptJson()
            ->timeout(15)
            ->post(self::API_URL.'/checkout/preferences', [
                'items' => $items,
                'external_reference' => (string) $order->id,
                ...($publicAppUrl ? [
                    'back_urls' => [
                        'success' => route('orders.complete', ['order' => $order]),
                        'failure' => route('orders.pay', ['order' => $order]),
                        'pending' => route('orders.complete', ['order' => $order]),
                    ],
                    'auto_return' => 'approved',
                ] : []),
                'payment_methods' => [
                    'excluded_payment_types' => [
                        ['id' => 'ticket'],
                    ],
                    'installments' => 1,
                ],
                'notification_url' => route('webhooks'),
            ]);

        $response->throw();

        return $response->json();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function fetchPayment(string $paymentId): ?array
    {
        $response = Http::withToken(config('services.mercadopago.token'))
            ->acceptJson()
            ->timeout(10)
            ->get(self::API_URL.'/v1/payments/'.urlencode($paymentId));

        if ($response->successful()) {
            return $response->json();
        }

        if ($response->notFound()) {
            return null;
        }

        $response->throw();
    }

    public function verifyWebhookSignature(Request $request): bool
    {
        $secret = config('services.mercadopago.webhook_secret');

        if (blank($secret)) {
            Log::error('MP_WEBHOOK_SECRET is not configured; rejecting Mercado Pago webhook.');

            return false;
        }

        $signature = (string) $request->header('x-signature', '');
        $requestId = (string) $request->header('x-request-id', '');
        $dataId = (string) $request->input('data.id', '');

        if ($signature === '' || $requestId === '' || $dataId === '') {
            return false;
        }

        $parts = [];

        foreach (explode(',', $signature) as $part) {
            [$key, $value] = array_pad(explode('=', trim($part), 2), 2, null);

            if ($key !== null && $value !== null) {
                $parts[$key] = $value;
            }
        }

        $ts = $parts['ts'] ?? null;
        $v1 = $parts['v1'] ?? null;

        if (blank($ts) || blank($v1)) {
            return false;
        }

        $canonical = "id:{$dataId};request-id:{$requestId};ts:{$ts};";

        return hash_equals(hash_hmac('sha256', $canonical, $secret), $v1);
    }

    /**
     * Persist the payment on the order and generate the invoice once.
     *
     * @param  array<string, mixed>  $payment
     */
    public function applyPayment(Order $order, array $payment): void
    {
        if ((string) ($payment['external_reference'] ?? '') !== (string) $order->id) {
            throw new RuntimeException('El pago no pertenece a la orden.');
        }

        $amount = round((float) ($payment['transaction_amount'] ?? 0), 2);

        if ($order->total !== null && $amount !== round((float) $order->total, 2)) {
            throw new RuntimeException('El monto del pago no coincide con la orden.');
        }

        $status = $payment['status'] ?? null;

        $shouldCreateInvoice = DB::transaction(function () use ($order, $payment, $status) {
            $locked = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();

            $isDowngrade = in_array($status, [null, 'pending', 'in_process', 'rejected', 'cancelled'], true);
            $isOtherPaymentStatus = in_array($status, ['refunded', 'charged_back', 'in_mediation'], true)
                && (string) ($payment['id'] ?? '') !== (string) $locked->payment_id;

            if ($locked->payment_status === 'approved' && ($isDowngrade || $isOtherPaymentStatus)) {
                $order->setRawAttributes($locked->getAttributes(), true);

                return false;
            }

            $shouldCreateInvoice = $status === 'approved' && blank($locked->invoice);
            $paymentItems = $payment['additional_info']['items'] ?? [];

            $attributes = [
                'payment_id' => $payment['id'] ?? null,
                'payment_status' => $status,
            ];

            if (blank($locked->items) && $paymentItems !== []) {
                $attributes['items'] = $paymentItems;
            }

            $locked->update($attributes);

            $order->setRawAttributes($locked->getAttributes(), true);

            return $shouldCreateInvoice;
        });

        if ($shouldCreateInvoice) {
            Cache::lock("mp-order-invoice-{$order->id}", 30)->block(5, function () use ($order, $payment) {
                if (blank($order->fresh()->invoice)) {
                    $items = filled($order->items)
                        ? $order->items
                        : ($payment['additional_info']['items'] ?? []);

                    CreateOrderEvent::dispatch($items, $order);
                }
            });
        }
    }

    /**
     * Reconcile an order with Mercado Pago when the webhook or back URL was missed.
     */
    public function syncPayment(Order $order): ?string
    {
        if ($order->payment_status === 'approved') {
            return $order->payment_status;
        }

        try {
            $response = Http::withToken(config('services.mercadopago.token'))
                ->acceptJson()
                ->timeout(10)
                ->get(self::API_URL.'/v1/payments/search', [
                    'external_reference' => (string) $order->id,
                    'sort' => 'date_created',
                    'criteria' => 'desc',
                ]);
        } catch (Throwable $exception) {
            Log::warning('Could not reach Mercado Pago to sync an order payment.', [
                'order_id' => $order->id,
                'reason' => $exception->getMessage(),
            ]);

            return $order->payment_status;
        }

        if (! $response->successful()) {
            Log::warning('Mercado Pago rejected the payment search for an order.', [
                'order_id' => $order->id,
                'status' => $response->status(),
            ]);

            return $order->payment_status;
        }

        $payments = collect($response->json('results', []));
        $payment = $payments->firstWhere('status', 'approved') ?? $payments->first();

        if (! $payment) {
            return $order->payment_status;
        }

        try {
            $this->applyPayment($order, $payment);
        } catch (RuntimeException $exception) {
            Log::warning('Mercado Pago payment rejected for order.', [
                'order_id' => $order->id,
                'payment_id' => $payment['id'] ?? null,
                'reason' => $exception->getMessage(),
            ]);
        }

        return $order->payment_status;
    }
}

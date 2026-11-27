<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\MercadoPagoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class WebhooksController extends Controller
{
    public function __invoke(Request $request, MercadoPagoService $mercadopago)
    {
        if (! $mercadopago->verifyWebhookSignature($request)) {
            abort(401);
        }

        $type = $request->input('type') ?? $request->input('topic');

        if ($type !== 'payment') {
            return response()->noContent();
        }

        $payment = $mercadopago->fetchPayment((string) $request->input('data.id'));

        if (! $payment) {
            return response()->noContent(500);
        }

        $order = Order::find($payment['external_reference'] ?? null);

        if (! $order) {
            return response()->noContent();
        }

        try {
            $mercadopago->applyPayment($order, $payment);
        } catch (RuntimeException $exception) {
            Log::warning('Mercado Pago payment rejected for order.', [
                'order_id' => $order->id,
                'payment_id' => $payment['id'] ?? null,
                'reason' => $exception->getMessage(),
            ]);
        }

        return response()->noContent();
    }
}

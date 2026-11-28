<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Services\MercadoPagoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('orders.index', [
            'orderStatuses' => OrderStatus::cases(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $cart = Cart::findOrFail($id);
        $this->authorize('author', $cart);

        return view('orders.create', [
            'cart' => $cart,
        ]);
    }

    public function payOrder(Order $order, Request $request, MercadoPagoService $mercadopago)
    {
        $this->authorize('author', $order);

        if ($order->isPaid()) {
            return redirect()->route('orders.complete', ['order' => $order]);
        }

        $paymentId = $request->input('payment_id') ?? $request->input('collection_id');

        if (is_string($paymentId) && $paymentId !== '') {
            try {
                $payment = $mercadopago->fetchPayment($paymentId);

                if ($payment) {
                    $mercadopago->applyPayment($order, $payment);
                }
            } catch (Throwable $exception) {
                Log::warning('Could not apply the Mercado Pago payment returned to the pay page.', [
                    'order_id' => $order->id,
                    'payment_id' => $paymentId,
                    'reason' => $exception->getMessage(),
                ]);
            }
        }

        $shouldSync = blank($paymentId) && (
            (in_array($order->payment_status, ['pending', 'in_process'], true) && blank($order->payment_id))
            || in_array($order->payment_status, ['rejected', 'cancelled'], true)
            || (in_array($order->payment_status, [null, 'rejected', 'cancelled'], true) && blank($order->items))
        );

        if ($shouldSync) {
            $mercadopago->syncPayment($order);
        }

        if ($order->canRetryPayment()) {
            $this->backfillItemsFromActiveCart($order);
        }

        if ($order->isPaid()
            || $order->isPaymentPending()
            || in_array($order->payment_status, ['refunded', 'charged_back', 'in_mediation'], true)) {
            return redirect()->route('orders.complete', ['order' => $order]);
        }

        $checkoutUrl = null;
        $error = null;

        if (blank($order->items)) {
            $error = 'No pudimos recuperar los productos de este pedido. Escríbenos para ayudarte a completar el pago.';
        } elseif (blank(config('services.mercadopago.token'))) {
            $error = 'El pago con Mercado Pago no está configurado. Intenta más tarde.';
        } else {
            $itemsTotal = (int) collect($order->items)->sum(
                fn (array $item): int => (int) ($item['unit_price'] ?? 0) * (int) ($item['quantity'] ?? 0)
            );

            if ($order->total === null) {
                $order->update(['total' => $itemsTotal]);
            } elseif ($itemsTotal !== (int) $order->total) {
                $error = 'El detalle del pedido no coincide con el total. Escríbenos para revisarlo antes de pagar.';
            }

            $unavailableItems = $error === null ? $this->unavailableItems($order) : [];

            if ($error === null && $unavailableItems !== []) {
                $error = 'Estos productos ya no tienen stock suficiente: '.implode(', ', $unavailableItems).'. Escríbenos para actualizar tu pedido.';
            } elseif ($error === null) {
                try {
                    $preference = $mercadopago->createPreference($order);

                    $checkoutUrl = str_starts_with((string) config('services.mercadopago.key'), 'TEST-')
                        ? ($preference['sandbox_init_point'] ?? $preference['init_point'] ?? null)
                        : ($preference['init_point'] ?? $preference['sandbox_init_point'] ?? null);

                    if (blank($checkoutUrl)) {
                        $error = 'No pudimos obtener el enlace de pago de Mercado Pago. Intenta de nuevo.';
                    }
                } catch (Throwable $exception) {
                    report($exception);
                    $error = 'No pudimos iniciar el pago con Mercado Pago. Revisa tu conexión e intenta de nuevo.';
                }
            }
        }

        return view('orders.pay', [
            'order' => $order,
            'checkoutUrl' => $checkoutUrl,
            'error' => $error,
        ]);
    }

    public function legacyPayOrder($id, Order $order)
    {
        $cart = Cart::with('products')->findOrFail($id);

        $this->authorize('author', $cart);
        $this->authorize('author', $order);

        if ($order->isPaid()) {
            return redirect()->route('orders.complete', ['order' => $order]);
        }

        if (blank($order->items) && $cart->total() === (int) $order->total) {
            $order->update(['items' => $cart->toOrderItems()]);
        }

        return redirect()->route('orders.pay', ['order' => $order]);
    }

    private function backfillItemsFromActiveCart(Order $order): void
    {
        if (filled($order->items)) {
            return;
        }

        $cart = Auth::user()->activeCart()->with('products')->first();

        if ($cart && $cart->total() === (int) $order->total) {
            $order->update(['items' => $cart->toOrderItems()]);
        }
    }

    /**
     * @return array<int, string>
     */
    private function unavailableItems(Order $order): array
    {
        $items = collect($order->items ?? []);
        $products = Product::whereIn('id', $items->pluck('id')->filter()->all())->get()->keyBy('id');

        return $items
            ->filter(function (array $item) use ($products): bool {
                $product = $products->get((string) ($item['id'] ?? ''));

                return ! $product || $product->stock < (int) ($item['quantity'] ?? 0);
            })
            ->map(fn (array $item): string => (string) ($item['title'] ?? 'Producto'))
            ->values()
            ->all();
    }

    public function paymentStatus(Order $order, MercadoPagoService $mercadopago)
    {
        $this->authorize('author', $order);

        $status = $mercadopago->syncPayment($order);

        return response()->json([
            'status' => $status,
            'label' => $order->paymentStatusLabel(),
            'paid' => $status === 'approved',
            'payment_id' => $order->payment_id,
            'redirect' => $status === 'approved'
                ? ($order->payment_id
                    ? route('orders.complete', ['order' => $order, 'payment_id' => $order->payment_id])
                    : route('orders.index'))
                : null,
            'can_retry' => $order->canRetryPayment(),
            'resume_url' => $order->canRetryPayment()
                ? route('orders.pay', ['order' => $order])
                : null,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function showInvoice(string $name)
    {
        $order = Order::where('invoice', $name)->first();

        if (! $order) {
            abort(404);
        }

        $user = Auth::user();

        if ($order->customer_id !== $user->id && ! $user->can('ViewAny:Order')) {
            abort(404);
        }

        return response()->file(
            Storage::disk('invoices')->path($order->invoice),
            ['Content-Disposition' => 'inline; filename="'.$order->invoiceFileName().'"']
        );
    }

    public function orderComplete(Order $order, Request $request, MercadoPagoService $mercadopago)
    {
        $this->authorize('author', $order);

        if ($order->payment_status === 'approved') {
            return view('orders.payComplete', [
                'order' => $order,
                'status' => 'approved',
            ]);
        }

        $paymentId = $request->input('payment_id') ?? $request->input('collection_id');

        if (! is_string($paymentId) || $paymentId === '') {
            return view('orders.payComplete', [
                'order' => $order,
                'status' => $mercadopago->syncPayment($order),
            ]);
        }

        $payment = $mercadopago->fetchPayment($paymentId);

        if (! $payment) {
            abort(404);
        }

        try {
            $mercadopago->applyPayment($order, $payment);
        } catch (RuntimeException) {
            abort(403);
        }

        return view('orders.payComplete', [
            'order' => $order,
            'status' => $order->payment_status,
        ]);
    }
}

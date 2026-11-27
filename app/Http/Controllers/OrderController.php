<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Cart;
use App\Models\Order;
use App\Services\MercadoPagoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

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

    public function payOrder($id, Order $order, MercadoPagoService $mercadopago)
    {
        if ($order->payment_status === 'approved') {
            return redirect()->route('orders.index');
        }

        $cart = Cart::with(['products' => function ($query) {
            $query->select('products.id', 'products.name', 'products.price', 'products.stock')
                ->where('products.stock', '>', 0);
        }])->findOrFail($id);

        $this->authorize('author', $cart);
        $this->authorize('author', $order);

        $order->update(['total' => $cart->total()]);

        return view('orders.pay', [
            'cart' => $cart,
            'order' => $order,
            'preference' => $mercadopago->createPreference($order, $cart),
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

        return response()->file(Storage::disk('invoices')->path($order->invoice));
    }

    public function orderComplete(Order $order, Request $request, MercadoPagoService $mercadopago)
    {
        $this->authorize('author', $order);

        $paymentId = $request->input('payment_id') ?? $request->input('collection_id');

        if (! is_string($paymentId) || $paymentId === '') {
            return redirect()->route('orders.index');
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
            'status' => $payment['status'],
        ]);
    }
}

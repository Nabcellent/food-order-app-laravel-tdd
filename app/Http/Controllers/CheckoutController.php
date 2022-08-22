<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Repositories\CartRepository;

class CheckoutController extends Controller
{
    public function index(CartRepository $cart)
    {
        $checkout_items = $cart->get();
        $total = $cart->total();


        return view('checkout', compact('checkout_items', 'total'));
    }

    public function create(CartRepository $cart)
    {
        $checkoutItems = $cart->get();
        $total = $cart->total();

        $order = Order::create(['total' => $total]);

        foreach ($checkoutItems as $item) {
            $order->detail()->create([
                'product_id' => $item['id'],
                'cost' => $item['cost'],
                'qty' => $item['qty'],
            ]);
        }

        return redirect('/summary');
    }
}

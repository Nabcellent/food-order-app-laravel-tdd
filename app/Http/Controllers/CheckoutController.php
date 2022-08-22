<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Repositories\CartRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class CheckoutController extends Controller
{
    public function index(CartRepository $cart): Factory|View|Application
    {
        $checkout_items = $cart->get();
        $total = $cart->total();


        return view('checkout', compact('checkout_items', 'total'));
    }

    public function create(CartRepository $cart): Redirector|Application|RedirectResponse
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

<?php

namespace App\Http\Controllers;

use App\Repositories\CartRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class CartController extends Controller
{
    public function index(CartRepository $cart): Factory|View|Application
    {
        $cartItems = $cart->get();

        return view('cart', ["cartItems" => $cartItems]);
    }

    public function store(CartRepository $cart): Redirector|Application|RedirectResponse
    {
        $cart->add(request('id'));

        return redirect('/cart');
    }

    public function update(CartRepository $cart): Redirector|Application|RedirectResponse
    {
        $cart->update(request('id'), request('qty'));

        return redirect('/cart');
    }

    public function destroy(CartRepository $cart): Redirector|Application|RedirectResponse
    {
        $id = request('id');
        $cart->remove($id);

        return redirect('/cart');
    }
}

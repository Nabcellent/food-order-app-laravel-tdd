<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class CartController extends Controller
{
    public function index(): Factory|View|Application
    {
        $items = Product::whereIn('id', collect(session('cart'))->pluck('id'))->get();

        $cartItems = collect(session('cart'))->map(function($row, $index) use ($items) {
            return [
                'id'    => $row['id'],
                'qty'   => $row['qty'],
                'name'  => $items[$index]->name,
                'image' => $items[$index]->image,
                'cost'  => $items[$index]->cost,
            ];
        })->toArray();

        return view('cart', ["cartItems" => $cartItems]);
    }

    public function store()
    {
        $existing = collect(session('cart'))->first(function($row, $key) {
            return $row['id'] == request('id');
        });

        if(!$existing) {
            session()->push('cart', [
                'id'  => request('id'),
                'qty' => 1,
            ]);
        }

        return redirect('/cart');
    }

    public function update(): Redirector|Application|RedirectResponse
    {
        $id = request('id');
        $qty = request('qty');

        $items = collect(session('cart'))->map(function($row) use ($id, $qty) {
            if($row['id'] == $id) {
                return ['id' => $row['id'], 'qty' => $qty];
            }
            return $row;
        })->toArray();

        session(['cart' => $items]);

        return redirect('/cart');
    }

    public function destroy(): Redirector|Application|RedirectResponse
    {
        $id = request('id');
        $items = collect(session('cart'))->filter(function($item) use ($id) {
            return $item['id'] != $id;
        })->values()->toArray();

        session(['cart' => $items]);

        return redirect('/cart');
    }
}

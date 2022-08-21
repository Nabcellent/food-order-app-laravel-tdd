<?php

namespace App\Http\Controllers;

class CartController extends Controller
{
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
}

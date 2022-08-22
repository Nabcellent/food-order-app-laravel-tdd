<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Support\Collection;

class CartRepository
{
    private Collection $cart;
    private $items;

    public function __construct()
    {
        $this->cart = collect(session('cart'));
        $this->items = Product::whereIn('id', $this->cart->pluck('id'))->get();
    }

    public function get(): array
    {
        return $this->cart->map(function($row, $index) {
            $qty = (int)$row['qty'];
            $cost = (float)$this->items[$index]->cost;
            $subtotal = $cost * $qty;

            return [
                'id'       => $row['id'],
                'qty'      => $row['qty'],
                'name'     => $this->items[$index]->name,
                'image'    => $this->items[$index]->image,
                'cost'     => $this->items[$index]->cost,
                'subtotal' => round($subtotal, 2),
            ];
        })->toArray();
    }

    private function exists($id)
    {
        return $this->cart->first(fn($row, $key) => $row['id'] == $id);
    }

    public function add($id): bool
    {
        $existing = $this->exists($id);

        if(!$existing) {
            session()->push('cart', [
                'id'  => $id,
                'qty' => 1,
            ]);
            return true;
        }

        return false;
    }

    public function remove($id): void
    {
        $items = $this->cart->filter(function($item) use ($id) {
            return $item['id'] != $id;
        })->values()->toArray();

        session(['cart' => $items]);
    }

    public function update($id, $qty): void
    {
        $items = $this->cart->map(function($row) use ($id, $qty) {
            if($row['id'] == $id) {
                return ['id' => $row['id'], 'qty' => $qty];
            }
            return $row;
        })->toArray();

        session(['cart' => $items]);
    }

    public function total()
    {
        $items = collect($this->get());
        return $items->sum('subtotal');
    }
}

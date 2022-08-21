<?php

namespace App\Http\Controllers;

use App\Models\Product;

class SearchProductsController extends Controller
{
    public function index()
    {
        $queryStr = request('query');
        $items = Product::when($queryStr, function($query, $queryStr) {
            return $query->where('name', 'LIKE', "%{$queryStr}%");
        })->get();

        return view('search', compact('items'));
    }
}

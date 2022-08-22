<?php

namespace App\Http\Controllers;

use App\Models\Product;

class SearchProductsController extends Controller
{
    public function index()
    {
        $queryStr = request('query');
        $items = Product::matches($queryStr)->get();

        return view('search', compact('items'));
    }
}

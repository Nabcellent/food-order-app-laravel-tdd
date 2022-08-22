<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class SearchProductsController extends Controller
{
    public function index(): Factory|View|Application
    {
        $queryStr = request('query');
        $items = Product::matches($queryStr)->get();

        return view('search', compact('items'));
    }
}

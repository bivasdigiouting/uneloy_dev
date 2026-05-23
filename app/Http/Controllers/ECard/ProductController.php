<?php

namespace App\Http\Controllers\ECard;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::with(['products' => function($query) {
            $query->where('is_active', true);
        }])->where('status', 'active')->get();

        return view('ecard.products.index', compact('categories'));
    }
}

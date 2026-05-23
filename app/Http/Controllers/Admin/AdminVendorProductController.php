<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class AdminVendorProductController extends Controller
{
    public function index()
    {
        $products = Product::whereNotNull('vendor_id')->with('vendor')->latest()->get();
        return view('admin.vendor_products.index', compact('products'));
    }

    public function approve($id)
    {
        $product = Product::findOrFail($id);
        $product->admin_status = 'approved';
        $product->save();

        return redirect()->back()->with('success', 'Vendor Product Approved Successfully!');
    }

    public function reject($id)
    {
        $product = Product::findOrFail($id);
        $product->admin_status = 'rejected';
        $product->save();

        return redirect()->back()->with('success', 'Vendor Product Rejected Successfully!');
    }
}

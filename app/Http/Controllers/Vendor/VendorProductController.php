<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VendorProductController extends Controller
{
    private function getAuthenticatedVendorId()
    {
        return session('vendor_id');
    }

    public function index()
    {
        $vendorId = $this->getAuthenticatedVendorId();
        if (!$vendorId) return redirect()->route('vendor.login');

        $products = Product::where('vendor_id', $vendorId)->latest()->get();
        $vendor = session('vendor_data');
        return view('vendor.products.index', [
            'vendor' => $vendor,
            'title' => 'Products',
            'activePage' => 'Products',
            'products' => $products
        ]);
    }

    public function store(Request $request)
    {
        $vendorId = $this->getAuthenticatedVendorId();
        if (!$vendorId) return redirect()->route('vendor.login');

        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'name' => $request->name,
            'category' => $request->category,
            'price' => $request->price,
            'stock' => $request->stock,
            'detail' => $request->name, // using name as detail fallback
            'image' => $imagePath ?? '',
            'vendor_id' => $vendorId,
            'admin_status' => 'pending' // Default to pending
        ]);

        return redirect()->route('vendor.products')->with('success', 'Product generated and sent to Admin for approval.');
    }

    public function destroy($id)
    {
        $vendorId = $this->getAuthenticatedVendorId();
        if (!$vendorId) return redirect()->route('vendor.login');

        $product = Product::where('id', $id)->where('vendor_id', $vendorId)->firstOrFail();
        $product->delete();

        return redirect()->route('vendor.products')->with('success', 'Product deleted successfully.');
    }
}

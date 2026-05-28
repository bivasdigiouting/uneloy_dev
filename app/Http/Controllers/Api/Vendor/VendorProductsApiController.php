<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class VendorProductsApiController extends Controller
{
    private function vendorId(Request $request): ?int
    {
        $vendor = $request->user();
        return $vendor ? (int) $vendor->id : null;
    }

    public function index(Request $request)
    {
        $vendorId = $this->vendorId($request);
        if (! $vendorId) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $products = Product::where('vendor_id', $vendorId)->latest()->get();

        return response()->json([
            'success' => true,
            'data' => ['products' => $products],
        ]);
    }

    public function store(Request $request)
    {
        $vendorId = $this->vendorId($request);
        if (! $vendorId) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product = Product::create([
            'name' => $request->name,
            'category' => $request->category,
            'price' => $request->price,
            'stock' => $request->stock,
            'detail' => $request->name,
            'image' => $imagePath ?? '',
            'vendor_id' => $vendorId,
            'admin_status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product created and submitted for admin approval.',
            'data' => ['product' => $product],
        ], 201);
    }

    public function destroy(Request $request, int $id)
    {
        $vendorId = $this->vendorId($request);
        if (! $vendorId) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $product = Product::where('id', $id)->where('vendor_id', $vendorId)->first();
        if (! $product) {
            return response()->json(['success' => false, 'message' => 'Product not found'], 404);
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted',
        ]);
    }
}


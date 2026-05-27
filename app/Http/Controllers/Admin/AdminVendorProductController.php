<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class AdminVendorProductController extends Controller
{
    public function index()
    {
        // Include vendor relation data for UI (prevents blank vendor column)
        // NOTE: Vendor table columns are vendor_name/mobile_no (not name/mobile)
        $products = Product::query()
            ->whereNotNull('vendor_id')
            ->with(['vendor:id,vendor_name,mobile_no'])
            ->latest()
            ->get();


        return view('admin.vendor_products.index', compact('products'));
    }


    public function approve($id)
    {
        try {
            $product = Product::findOrFail($id);

            $product->admin_status = 'approved';
            $product->save();

            return redirect()->back()->with('success', 'Vendor Product Approved Successfully!');
        } catch (\InvalidArgumentException $e) {
            \Log::error('Vendor product approve failed (InvalidArgumentException)', [
                'product_id' => $id,
                'message' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Approval failed: ' . $e->getMessage());
        } catch (\Throwable $e) {
            \Log::error('Vendor product approve failed (Throwable)', [
                'product_id' => $id,
                'message' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Approval failed. Please check logs.');
        }
    }

    public function reject($id)
    {
        try {
            $product = Product::findOrFail($id);

            $product->admin_status = 'rejected';
            $product->save();

            return redirect()->back()->with('success', 'Vendor Product Rejected Successfully!');
        } catch (\InvalidArgumentException $e) {
            \Log::error('Vendor product reject failed (InvalidArgumentException)', [
                'product_id' => $id,
                'message' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Rejection failed: ' . $e->getMessage());
        } catch (\Throwable $e) {
            \Log::error('Vendor product reject failed (Throwable)', [
                'product_id' => $id,
                'message' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Rejection failed. Please check logs.');
        }
    }
}


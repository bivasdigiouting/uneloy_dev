<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\ShoppingRepositoryInterface;
use Illuminate\Http\Request;

class ShoppingController extends Controller
{
    protected ShoppingRepositoryInterface $shoppingRepository;

    public function __construct(ShoppingRepositoryInterface $shoppingRepository)
    {
        $this->shoppingRepository = $shoppingRepository;
    }

    public function edit()
    {
        $shopping = $this->shoppingRepository->getShopping();

        return view('admin.e-store.shoppings', compact('shopping'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'text_header' => 'nullable|string|max:255',
            'text_description' => 'nullable|string',
            'footer_short_description' => 'nullable|string',
        ]);

        try {
            $this->shoppingRepository->update($request->all());

            return redirect()->back()->with('success', 'Shoppings content updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating Shoppings content: '.$e->getMessage());
        }
    }
}

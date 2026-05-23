<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GstTax;
use App\Models\InhouseProduct;
use App\Models\InhouseProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class InhouseProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = InhouseProduct::query()
                ->leftJoin('inhouse_product_categories as ipc', 'inhouse_products.inhouse_product_category_id', '=', 'ipc.id')
                ->select([
                    'inhouse_products.id',
                    'inhouse_products.name',
                    'inhouse_products.sku',
                    'inhouse_products.mrp',
                    'inhouse_products.price',
                    'inhouse_products.stock',
                    'inhouse_products.thumbnail',
                    'inhouse_products.is_active',
                    'inhouse_products.created_at',
                    'ipc.name as category_name',
                ])
                ->orderBy('inhouse_products.id', 'desc');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('thumbnail_display', function ($row) {
                    if (! $row->thumbnail) {
                        return '<span class="text-muted">No Image</span>';
                    }

                    return '<img src="'.asset('storage/'.$row->thumbnail).'" alt="Thumbnail" style="width: 48px; height: 48px; object-fit: cover;" class="rounded border">';
                })
                ->addColumn('status_display', function ($row) {
                    return $row->is_active ? 1 : 0;
                })
                ->rawColumns(['thumbnail_display'])
                ->make(true);
        }

        return view('admin.inhouse-products.index');
    }

    public function create()
    {
        $categories = InhouseProductCategory::query()
            ->where('status', 'active')
            ->orderBy('display_order', 'asc')
            ->orderBy('name', 'asc')
            ->get();

        $taxes = GstTax::query()->where('is_active', true)->orderBy('tax_name')->get();

        return view('admin.inhouse-products.create', compact('categories', 'taxes'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'inhouse_product_category_id' => ['required', 'exists:inhouse_product_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:64', 'unique:inhouse_products,sku'],
            'gst_tax_id' => ['nullable', 'exists:gst_taxes,id'],
            'mrp' => ['nullable', 'numeric', 'min:0'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
            'is_active' => ['required', 'boolean'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = [
            'inhouse_product_category_id' => (int) $request->input('inhouse_product_category_id'),
            'name' => trim((string) $request->input('name')),
            'sku' => trim((string) $request->input('sku')),
            'gst_tax_id' => $request->input('gst_tax_id') ?: null,
            'mrp' => $request->input('mrp'),
            'price' => $request->input('price'),
            'stock' => (int) $request->input('stock', 0),
            'description' => $request->input('description'),
            'is_active' => (bool) $request->input('is_active'),
        ];

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('inhouse-products/thumbnails', 'public');
        }

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $imagePaths[] = $file->store('inhouse-products/images', 'public');
            }
        }
        $data['images'] = $imagePaths ?: null;

        InhouseProduct::query()->create($data);

        return redirect()->route('admin.inhouse-products.index')->with('success', 'Inhouse product created successfully.');
    }

    public function show(int $id)
    {
        $product = InhouseProduct::query()->with(['category', 'gstTax'])->findOrFail($id);

        return view('admin.inhouse-products.show', compact('product'));
    }

    public function edit(int $id)
    {
        $product = InhouseProduct::query()->findOrFail($id);
        $categories = InhouseProductCategory::query()
            ->where('status', 'active')
            ->orderBy('display_order', 'asc')
            ->orderBy('name', 'asc')
            ->get();
        $taxes = GstTax::query()->where('is_active', true)->orderBy('tax_name')->get();

        return view('admin.inhouse-products.edit', compact('product', 'categories', 'taxes'));
    }

    public function update(Request $request, int $id)
    {
        $product = InhouseProduct::query()->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'inhouse_product_category_id' => ['required', 'exists:inhouse_product_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:64', 'unique:inhouse_products,sku,'.$product->id],
            'gst_tax_id' => ['nullable', 'exists:gst_taxes,id'],
            'mrp' => ['nullable', 'numeric', 'min:0'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
            'is_active' => ['required', 'boolean'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = [
            'inhouse_product_category_id' => (int) $request->input('inhouse_product_category_id'),
            'name' => trim((string) $request->input('name')),
            'sku' => trim((string) $request->input('sku')),
            'gst_tax_id' => $request->input('gst_tax_id') ?: null,
            'mrp' => $request->input('mrp'),
            'price' => $request->input('price'),
            'stock' => (int) $request->input('stock', 0),
            'description' => $request->input('description'),
            'is_active' => (bool) $request->input('is_active'),
        ];

        if ($request->hasFile('thumbnail')) {
            if ($product->thumbnail && Storage::disk('public')->exists($product->thumbnail)) {
                Storage::disk('public')->delete($product->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('inhouse-products/thumbnails', 'public');
        }

        $existingImages = is_array($product->images) ? $product->images : [];
        $imagePaths = $existingImages;
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $imagePaths[] = $file->store('inhouse-products/images', 'public');
            }
        }
        $data['images'] = $imagePaths ?: null;

        $product->update($data);

        return redirect()->route('admin.inhouse-products.index')->with('success', 'Inhouse product updated successfully.');
    }

    public function destroy(int $id)
    {
        $product = InhouseProduct::query()->findOrFail($id);

        if ($product->thumbnail && Storage::disk('public')->exists($product->thumbnail)) {
            Storage::disk('public')->delete($product->thumbnail);
        }

        $images = is_array($product->images) ? $product->images : [];
        foreach ($images as $img) {
            if ($img && Storage::disk('public')->exists($img)) {
                Storage::disk('public')->delete($img);
            }
        }

        $product->delete();

        return response()->json(['success' => true]);
    }

    public function toggleStatus(int $id)
    {
        $product = InhouseProduct::query()->findOrFail($id);
        $product->is_active = ! $product->is_active;
        $product->save();

        return response()->json(['success' => true, 'status' => $product->is_active ? 1 : 0]);
    }
}

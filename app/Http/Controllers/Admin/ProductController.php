<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\GstTaxRepositoryInterface;
use App\Repositories\Interfaces\ProductCategoryRepositoryInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    protected ProductRepositoryInterface $productRepository;

    protected ProductCategoryRepositoryInterface $productCategoryRepository;

    protected GstTaxRepositoryInterface $gstTaxRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        ProductCategoryRepositoryInterface $productCategoryRepository,
        GstTaxRepositoryInterface $gstTaxRepository
    ) {
        $this->productRepository = $productRepository;
        $this->productCategoryRepository = $productCategoryRepository;
        $this->gstTaxRepository = $gstTaxRepository;
    }

    /**
     * Display a listing of the products.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $products = $this->productRepository->getForDataTable();

            return DataTables::of($products)
                ->addIndexColumn()
                ->addColumn('thumbnail', function ($product) {
                    if ($product->image) {
                        return '<img src="'.asset('storage/'.$product->image).'" alt="Thumb" style="width: 50px; height: 50px; object-fit: cover;" class="rounded">';
                    }

                    return '<span class="text-muted">No Image</span>';
                })
                ->addColumn('status', function ($product) {
                    return $product->is_active
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                })
                ->editColumn('mrp', function ($product) {
                    $val = $product->mrp ?? 0;

                    return number_format((float) $val, 2);
                })
                ->editColumn('distributor_price', function ($product) {
                    $val = $product->distributor_price ?? 0;

                    return number_format((float) $val, 2);
                })
                ->editColumn('price', function ($product) {
                    $val = $product->price ?? 0;

                    return number_format((float) $val, 2);
                })
                ->addColumn('action', function ($product) {
                    $actions = '<div class="btn-group" role="group">';
                    $actions .= '<a href="'.route('admin.products.edit', $product->id).'" class="btn btn-sm btn-primary" title="Edit"><i class="ti ti-edit"></i></a>';
                    $actions .= '<button type="button" class="btn btn-sm btn-warning" onclick="toggleProductStatus('.$product->id.')" title="Toggle Status"><i class="ti ti-toggle-left"></i></button>';
                    $actions .= '<button type="button" class="btn btn-sm btn-danger" onclick="deleteProduct('.$product->id.')" title="Delete"><i class="ti ti-trash"></i></button>';
                    $actions .= '</div>';

                    return $actions;
                })
                ->editColumn('created_at', function ($product) {
                    return $product->created_at ? $product->created_at->format('d M Y, h:i A') : '';
                })
                ->rawColumns(['thumbnail', 'status', 'action'])
                ->make(true);
        }

        return view('admin.products.index');
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = $this->productCategoryRepository->getActive();
        $taxes = $this->gstTaxRepository->getActive();

        return view('admin.products.create', compact('categories', 'taxes'));
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:product_categories,id',
            'name' => 'required|string|max:255',
            'mrp' => 'nullable|numeric|min:0',
            'distributor_price' => 'nullable|numeric|min:0',
            'price' => 'nullable|numeric|min:0',
            'gst_tax_id' => 'nullable|exists:gst_taxes,id',
            'description' => 'nullable|string',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:4096',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = [
            'name' => $request->input('name'),
            'category' => optional($this->productCategoryRepository->find($request->input('category_id')))->name,
            'price' => $request->input('price'),
            'mrp' => $request->input('mrp'),
            'distributor_price' => $request->input('distributor_price'),
            'gst_tax_id' => $request->input('gst_tax_id'),
            'description' => $request->input('description'),
            'is_active' => true,
        ];

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('products/thumbnails', 'public');
            $data['image'] = $path;
        }

        // Handle multiple images upload
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $imagePaths[] = $file->store('products/images', 'public');
            }
        }
        if (! empty($imagePaths)) {
            $data['images'] = json_encode($imagePaths);
        }

        $product = $this->productRepository->create($data);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(int $id)
    {
        $product = $this->productRepository->find($id);
        if (! $product) {
            return redirect()->route('admin.products.index')->with('error', 'Product not found.');
        }
        $categories = $this->productCategoryRepository->getActive();
        $taxes = $this->gstTaxRepository->getActive();

        return view('admin.products.edit', compact('product', 'categories', 'taxes'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, int $id)
    {
        $product = $this->productRepository->find($id);
        if (! $product) {
            return redirect()->route('admin.products.index')->with('error', 'Product not found.');
        }

        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:product_categories,id',
            'name' => 'required|string|max:255',
            'mrp' => 'nullable|numeric|min:0',
            'distributor_price' => 'nullable|numeric|min:0',
            'price' => 'nullable|numeric|min:0',
            'gst_tax_id' => 'nullable|exists:gst_taxes,id',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = [
            'name' => $request->input('name'),
            'category' => optional($this->productCategoryRepository->find($request->input('category_id')))->name,
            'price' => $request->input('price'),
            'mrp' => $request->input('mrp'),
            'distributor_price' => $request->input('distributor_price'),
            'gst_tax_id' => $request->input('gst_tax_id'),
            'description' => $request->input('description'),
            'is_active' => $product->is_active,
        ];

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $path = $request->file('thumbnail')->store('products/thumbnails', 'public');
            $data['image'] = $path;
        }

        // Handle multiple images upload
        $existingImages = $product->images ?? [];
        $imagePaths = is_array($existingImages) ? $existingImages : (json_decode($existingImages, true) ?: []);
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $imagePaths[] = $file->store('products/images', 'public');
            }
        }
        $data['images'] = json_encode($imagePaths);

        if (! $product->image && ! $request->hasFile('thumbnail')) {
            return redirect()->back()->withErrors(['thumbnail' => 'Please upload a product thumbnail.'])->withInput();
        }
        $hasImages = ! empty($imagePaths) || ($product->images && count($imagePaths) > 0);
        if (! $hasImages && ! $request->hasFile('images')) {
            return redirect()->back()->withErrors(['images' => 'Please upload at least one product image.'])->withInput();
        }

        $this->productRepository->update($id, $data);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    /** Toggle status */
    public function toggleStatus(int $id)
    {
        $ok = $this->productRepository->toggleStatus($id);

        return response()->json(['success' => $ok]);
    }

    /** Delete product */
    public function destroy(int $id)
    {
        $product = $this->productRepository->find($id);
        if (! $product) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
        }
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $images = is_array($product->images) ? $product->images : (json_decode($product->images, true) ?: []);
        foreach ($images as $img) {
            Storage::disk('public')->delete($img);
        }
        $ok = $this->productRepository->delete($id);

        return response()->json(['success' => $ok]);
    }

    /** Data route for index */
    public function data(Request $request)
    {
        $products = $this->productRepository->getForDataTable();

        return DataTables::of($products)
            ->addIndexColumn()
            ->addColumn('thumbnail', function ($product) {
                if ($product->image) {
                    return '<img src="'.asset('storage/'.$product->image).'" alt="Thumb" style="width: 50px; height: 50px; object-fit: cover;" class="rounded">';
                }

                return '<span class="text-muted">No Image</span>';
            })
            ->addColumn('status', function ($product) {
                return $product->is_active
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-danger">Inactive</span>';
            })
            ->addColumn('action', function ($product) {
                $actions = '<div class="btn-group" role="group">';
                $actions .= '<a href="'.route('admin.products.edit', $product->id).'" class="btn btn-sm btn-primary" title="Edit"><i class="ti ti-edit"></i></a>';
                $actions .= '<button type="button" class="btn btn-sm btn-warning" onclick="toggleProductStatus('.$product->id.')" title="Toggle Status"><i class="ti ti-toggle-left"></i></button>';
                $actions .= '<button type="button" class="btn btn-sm btn-danger" onclick="deleteProduct('.$product->id.')" title="Delete"><i class="ti ti-trash"></i></button>';
                $actions .= '</div>';

                return $actions;
            })
            ->editColumn('created_at', function ($product) {
                return $product->created_at ? $product->created_at->format('d M Y, h:i A') : '';
            })
            ->rawColumns(['thumbnail', 'status', 'action'])
            ->make(true);
    }
}

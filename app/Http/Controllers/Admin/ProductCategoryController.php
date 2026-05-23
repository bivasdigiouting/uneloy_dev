<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\LevelWiseProductCommissionRepositoryInterface;
use App\Repositories\Interfaces\ProductCategoryRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ProductCategoryController extends Controller
{
    protected $productCategoryRepository;

    protected $commissionRepository;

    public function __construct(ProductCategoryRepositoryInterface $productCategoryRepository, LevelWiseProductCommissionRepositoryInterface $commissionRepository)
    {
        $this->productCategoryRepository = $productCategoryRepository;
        $this->commissionRepository = $commissionRepository;
    }

    /**
     * Display a listing of the product categories.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $productCategories = $this->productCategoryRepository->getForDataTable();

            return DataTables::of($productCategories)
                ->addIndexColumn()
                ->addColumn('icon_display', function ($category) {
                    if ($category->icon) {
                        return '<img src="'.asset('storage/'.$category->icon).'" alt="Icon" style="width: 40px; height: 40px; object-fit: cover;" class="rounded">';
                    }

                    return '<span class="text-muted">No Icon</span>';
                })
                ->addColumn('status', function ($category) {
                    return $category->status === 'active'
                        ? 1
                        : 0;
                })
                ->addColumn('commission_display', function ($category) {
                    return $category->commission.'%';
                })
                ->addColumn('commission_level_display', function ($category) {
                    return $category->commission_level.'%';
                })
                ->addColumn('action', function ($category) {
                    $actions = '<div class="btn-group" role="group">';
                    $actions .= '<a href="'.route('admin.product-categories.edit', $category->id).'" class="btn btn-sm btn-primary" title="Edit"><i class="ti ti-edit"></i></a>';
                    $actions .= '<button type="button" class="btn btn-sm btn-'.($category->status === 'active' ? 'warning' : 'success').'" onclick="toggleProductCategoryStatus('.$category->id.')" title="'.($category->status === 'active' ? 'Deactivate' : 'Activate').'"><i class="ti ti-'.($category->status === 'active' ? 'eye-off' : 'eye').'"></i></button>';
                    $actions .= '<button type="button" class="btn btn-sm btn-danger" onclick="deleteProductCategory('.$category->id.')" title="Delete"><i class="ti ti-trash"></i></button>';
                    $actions .= '</div>';

                    return $actions;
                })
                ->editColumn('created_at', function ($category) {
                    return $category->created_at->format('d M Y, h:i A');
                })
                ->rawColumns(['icon_display', 'action', 'status'])
                ->make(true);
        }

        $totalProductCategories = $this->productCategoryRepository->getTotalCount();
        $activeProductCategories = $this->productCategoryRepository->getActiveCount();
        $inactiveProductCategories = $this->productCategoryRepository->getInactiveCount();
        $avgCommission = $this->productCategoryRepository->getAverageCommission();

        $stats = [
            'total' => $totalProductCategories,
            'active' => $activeProductCategories,
            'inactive' => $inactiveProductCategories,
            'avg_commission' => number_format($avgCommission, 2),
        ];

        if ($request->boolean('stats_only')) {
            return response()->json(['stats' => $stats]);
        }

        return view('admin.product-categories.index', compact('stats'));
    }

    /**
     * Show the form for creating a new product category.
     */
    public function create()
    {
        return view('admin.product-categories.create');
    }

    /**
     * Store a newly created product category in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:product_categories,name',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'sequence' => 'required|integer|min:0',
            'commission' => 'required|numeric|min:0|max:100',
            'commission_level' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->only(['name', 'sequence', 'commission', 'commission_level', 'description', 'status']);

            if ($request->hasFile('icon')) {
                try {
                    $iconPath = $request->file('icon')->store('product-categories/icons', 'public');
                    $data['icon'] = $iconPath;
                } catch (\Throwable $t) {
                    Log::error('Product category icon upload failed: '.$t->getMessage());
                }
            }

            $category = $this->productCategoryRepository->create($data);

            $commissionData = [
                'product_category_id' => $category->id,
                'state_member_commission' => 0,
                'district_member_commission' => 0,
                'block_member_commission' => 0,
                'panchayat_member_commission' => 0,
                'village_member_commission' => 0,
                'customer_commission' => 0,
                'is_active' => $category->status === 'active',
            ];
            $existing = $this->commissionRepository->findByProductCategoryId($category->id);
            if ($existing) {
                $this->commissionRepository->update($existing->id, $commissionData);
            } else {
                $this->commissionRepository->create($commissionData);
            }

            return redirect()->route('admin.product-categories.index')
                ->with('success', 'Product category created successfully.');
        } catch (\Exception $e) {
            Log::error('Product category create failed: '.$e->getMessage());

            return redirect()->back()
                ->with('error', 'Failed to create product category. Please try again.')
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified product category.
     */
    public function edit($id)
    {
        $productCategory = $this->productCategoryRepository->find($id);

        return view('admin.product-categories.edit', compact('productCategory'));
    }

    /**
     * Display the specified product category.
     */
    public function show($id)
    {
        $productCategory = $this->productCategoryRepository->find($id);

        return view('admin.product-categories.show', compact('productCategory'));
    }

    /**
     * Update the specified product category in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:product_categories,name,'.$id,
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'sequence' => 'required|integer|min:0',
            'commission' => 'required|numeric|min:0|max:100',
            'commission_level' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $productCategory = $this->productCategoryRepository->find($id);
            $data = $request->only(['name', 'sequence', 'commission', 'commission_level', 'description', 'status']);

            // Handle icon upload
            if ($request->hasFile('icon')) {
                // Delete old icon if exists
                if ($productCategory->icon && Storage::disk('public')->exists($productCategory->icon)) {
                    Storage::disk('public')->delete($productCategory->icon);
                }

                $iconPath = $request->file('icon')->store('product-categories/icons', 'public');
                $data['icon'] = $iconPath;
            }

            $this->productCategoryRepository->update($id, $data);

            $commissionData = [
                'product_category_id' => $productCategory->id,
                'is_active' => $productCategory->status === 'active',
            ];
            $existing = $this->commissionRepository->findByProductCategoryId($productCategory->id);
            if ($existing) {
                $this->commissionRepository->update($existing->id, $commissionData);
            } else {
                $commissionData['state_member_commission'] = 0;
                $commissionData['district_member_commission'] = 0;
                $commissionData['block_member_commission'] = 0;
                $commissionData['panchayat_member_commission'] = 0;
                $commissionData['village_member_commission'] = 0;
                $commissionData['customer_commission'] = 0;
                $this->commissionRepository->create($commissionData);
            }

            return redirect()->route('admin.product-categories.index')
                ->with('success', 'Product category updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update product category. Please try again.')
                ->withInput();
        }
    }

    /**
     * Remove the specified product category from storage.
     */
    public function destroy($id)
    {
        try {
            $productCategory = $this->productCategoryRepository->find($id);

            // Delete icon if exists
            if ($productCategory->icon && Storage::disk('public')->exists($productCategory->icon)) {
                Storage::disk('public')->delete($productCategory->icon);
            }

            $this->productCategoryRepository->delete($id);

            return response()->json([
                'success' => true,
                'message' => 'Product category deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete product category. Please try again.',
            ]);
        }
    }

    /**
     * Toggle the status of the specified product category.
     */
    public function toggleStatus($id)
    {
        try {
            $productCategory = $this->productCategoryRepository->find($id);
            $newStatus = $productCategory->status === 'active' ? 'inactive' : 'active';

            $this->productCategoryRepository->updateStatus($id, $newStatus);

            return response()->json([
                'success' => true,
                'message' => 'Product category status updated successfully.',
                'status' => ucfirst($newStatus),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update product category status. Please try again.',
            ]);
        }
    }

    /**
     * Get active product categories for dropdown
     */
    public function getActiveProductCategories()
    {
        try {
            $productCategories = $this->productCategoryRepository->getActive();

            return response()->json([
                'success' => true,
                'data' => $productCategories->map(function ($category) {
                    return [
                        'id' => $category->id,
                        'name' => $category->name,
                        'commission' => $category->commission,
                        'commission_level' => $category->commission_level,
                    ];
                }),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch product categories.',
            ]);
        }
    }
}

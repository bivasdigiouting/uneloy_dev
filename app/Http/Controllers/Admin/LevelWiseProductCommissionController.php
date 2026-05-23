<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\LevelWiseProductCommissionRepositoryInterface;
use App\Repositories\Interfaces\ProductCategoryRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class LevelWiseProductCommissionController extends Controller
{
    protected $commissionRepository;

    protected $productCategoryRepository;

    public function __construct(
        LevelWiseProductCommissionRepositoryInterface $commissionRepository,
        ProductCategoryRepositoryInterface $productCategoryRepository
    ) {
        $this->commissionRepository = $commissionRepository;
        $this->productCategoryRepository = $productCategoryRepository;
    }

    /**
     * Display a listing of the commissions.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $commissions = $this->commissionRepository->getForDataTable();

            return DataTables::of($commissions)
                ->addIndexColumn()
                ->addColumn('product_category_name', function ($commission) {
                    return $commission->productCategory ? $commission->productCategory->name : 'N/A';
                })
                ->addColumn('state_member_commission_display', function ($commission) {
                    return $commission->state_member_commission.'%';
                })
                ->addColumn('district_member_commission_display', function ($commission) {
                    return $commission->district_member_commission.'%';
                })
                ->addColumn('block_member_commission_display', function ($commission) {
                    return $commission->block_member_commission.'%';
                })
                ->addColumn('panchayat_member_commission_display', function ($commission) {
                    return $commission->panchayat_member_commission.'%';
                })
                ->addColumn('village_member_commission_display', function ($commission) {
                    return $commission->village_member_commission.'%';
                })
                ->addColumn('customer_commission_display', function ($commission) {
                    return $commission->customer_commission.'%';
                })
                ->addColumn('status', function ($commission) {
                    return $commission->is_active ? 1 : 0;
                })
                ->addColumn('action', function ($commission) {
                    $actions = '<div class="btn-group" role="group">';
                    $actions .= '<a href="'.route('admin.level-wise-commissions.edit', $commission->id).'" class="btn btn-sm btn-primary" title="Edit"><i class="ti ti-edit"></i></a>';
                    $actions .= '<button type="button" class="btn btn-sm btn-danger delete-btn" data-id="'.$commission->id.'" title="Delete"><i class="ti ti-trash"></i></button>';
                    $actions .= '</div>';

                    return $actions;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $productCategories = $this->productCategoryRepository->getActive();
        $commissions = $this->commissionRepository->all();

        return view('admin.level-wise-commissions.index', compact('productCategories', 'commissions'));
    }

    /**
     * Show commission details for a specific product category.
     */
    public function showDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_category_id' => 'required|integer|exists:product_categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid product category selected.',
            ], 400);
        }

        $productCategoryId = $request->product_category_id;
        $productCategory = $this->productCategoryRepository->find($productCategoryId);
        $commission = $this->commissionRepository->findByProductCategoryId($productCategoryId);

        $commissionLevels = [
            'State e-Card Seva' => $commission ? $commission->state_member_commission : 0,
            'District e-Card Seva' => $commission ? $commission->district_member_commission : 0,
            'Block - e-Card Seva' => $commission ? $commission->block_member_commission : 0,
            'G P M e-Card Seva' => $commission ? $commission->panchayat_member_commission : 0,
            'e-Card Seva' => $commission ? $commission->village_member_commission : 0,
            'Member' => $commission ? $commission->customer_commission : 0,
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'product_category' => $productCategory,
                'commission' => $commission,
                'commission_levels' => $commissionLevels,
                'exists' => $commission ? true : false,
            ],
            'product_category' => [
                'id' => $productCategory->id,
                'name' => $productCategory->name,
                'commission_level_target' => (float) ($productCategory->getRawOriginal('commission_level') ?? 0),
            ],
        ]);
    }

    /**
     * Store or update commission data.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_category_id' => 'required|integer|exists:product_categories,id',
            'state_member_commission' => 'required|numeric|min:0|max:100',
            'district_member_commission' => 'required|numeric|min:0|max:100',
            'block_member_commission' => 'required|numeric|min:0|max:100',
            'panchayat_member_commission' => 'required|numeric|min:0|max:100',
            'village_member_commission' => 'required|numeric|min:0|max:100',
            'customer_commission' => 'required|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $productCategory = $this->productCategoryRepository->find($request->product_category_id);
            $target = (float) ($productCategory->getRawOriginal('commission_level') ?? 0);
            $sum = (float) (
                $request->state_member_commission +
                $request->district_member_commission +
                $request->block_member_commission +
                $request->panchayat_member_commission +
                $request->village_member_commission +
                $request->customer_commission
            );
            $sum = round($sum, 2);
            $target = round($target, 2);
            if ($sum !== $target) {
                return response()->json([
                    'success' => false,
                    'message' => 'Total of all levels ('.$sum.'%) must equal category Commission for Level ('.$target.'%).',
                ], 422);
            }

            $data = $request->only([
                'product_category_id',
                'state_member_commission',
                'district_member_commission',
                'block_member_commission',
                'panchayat_member_commission',
                'village_member_commission',
                'customer_commission',
            ]);
            $data['is_active'] = $request->has('is_active');

            // Check if commission already exists for this product category
            $existingCommission = $this->commissionRepository->findByProductCategoryId($request->product_category_id);

            if ($existingCommission) {
                // Update existing commission
                $this->commissionRepository->update($existingCommission->id, $data);
                $message = 'Commission updated successfully.';
            } else {
                // Create new commission
                $this->commissionRepository->create($data);
                $message = 'Commission created successfully.';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save commission. Please try again.',
            ], 500);
        }
    }

    /**
     * Show the details for editing the specified commission as JSON.
     */
    public function edit($id)
    {
        $commission = $this->commissionRepository->find($id);
        if (! $commission) {
            return response()->json([
                'success' => false,
                'message' => 'Commission not found.',
            ], 404);
        }

        $productCategory = $commission->productCategory;
        $target = $productCategory ? (float) ($productCategory->getRawOriginal('commission_level') ?? 0) : 0;

        return response()->json([
            'success' => true,
            'data' => $commission,
            'commission_level_target' => $target,
        ]);
    }

    /**
     * Update the specified commission in storage via AJAX.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'product_category_id' => 'required|integer|exists:product_categories,id',
            'state_member_commission' => 'required|numeric|min:0|max:100',
            'district_member_commission' => 'required|numeric|min:0|max:100',
            'block_member_commission' => 'required|numeric|min:0|max:100',
            'panchayat_member_commission' => 'required|numeric|min:0|max:100',
            'village_member_commission' => 'required|numeric|min:0|max:100',
            'customer_commission' => 'required|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Check if another commission exists for the same product category
        if ($this->commissionRepository->existsForProductCategory($request->product_category_id, $id)) {
            return response()->json([
                'success' => false,
                'message' => 'Commission already exists for this product category.',
            ], 409);
        }

        try {
            $productCategory = $this->productCategoryRepository->find($request->product_category_id);
            $target = (float) ($productCategory->getRawOriginal('commission_level') ?? 0);
            $sum = (float) (
                $request->state_member_commission +
                $request->district_member_commission +
                $request->block_member_commission +
                $request->panchayat_member_commission +
                $request->village_member_commission +
                $request->customer_commission
            );
            $sum = round($sum, 2);
            $target = round($target, 2);
            if ($sum !== $target) {
                return response()->json([
                    'success' => false,
                    'message' => 'Total of all levels ('.$sum.'%) must equal category Commission for Level ('.$target.'%).',
                ], 422);
            }
            $data = $request->only([
                'product_category_id',
                'state_member_commission',
                'district_member_commission',
                'block_member_commission',
                'panchayat_member_commission',
                'village_member_commission',
                'customer_commission',
            ]);
            $data['is_active'] = $request->has('is_active');

            $this->commissionRepository->update($id, $data);

            return response()->json([
                'success' => true,
                'message' => 'Commission updated successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update commission. Please try again.',
            ], 500);
        }
    }

    /**
     * Remove the specified commission from storage.
     */
    public function destroy($id)
    {
        try {
            $deleted = $this->commissionRepository->delete($id);

            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Commission deleted successfully.',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Commission not found.',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete commission. Please try again.',
            ], 500);
        }
    }

    /**
     * Toggle commission status.
     */
    public function toggleStatus($id)
    {
        try {
            $toggled = $this->commissionRepository->toggleStatus($id);

            if ($toggled) {
                return response()->json([
                    'success' => true,
                    'message' => 'Commission status updated successfully.',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Commission not found.',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update commission status. Please try again.',
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InhouseProduct;
use App\Repositories\Interfaces\EcardSevaProductCommissionRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class EcardSevaProductCommissionController extends Controller
{
    protected $commissionRepository;

    public function __construct(
        EcardSevaProductCommissionRepositoryInterface $commissionRepository
    ) {
        $this->commissionRepository = $commissionRepository;
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
                ->addColumn('product_name', function ($commission) {
                    return $commission->product ? $commission->product->name : 'N/A';
                })
                ->addColumn('state_member_commission_display', function ($commission) {
                    return $commission->state_member_commission . '%';
                })
                ->addColumn('district_member_commission_display', function ($commission) {
                    return $commission->district_member_commission . '%';
                })
                ->addColumn('block_member_commission_display', function ($commission) {
                    return $commission->block_member_commission . '%';
                })
                ->addColumn('panchayat_member_commission_display', function ($commission) {
                    return $commission->panchayat_member_commission . '%';
                })
                ->addColumn('village_member_commission_display', function ($commission) {
                    return $commission->village_member_commission . '%';
                })
                ->addColumn('customer_commission_display', function ($commission) {
                    return $commission->customer_commission . '%';
                })
                ->addColumn('status', function ($commission) {
                    return $commission->is_active ? 1 : 0;
                })
                ->addColumn('action', function ($commission) {
                    $actions = '<div class="btn-group" role="group">';
                    $actions .= '<a href="#" class="btn btn-sm btn-primary edit-commission" data-id="' . $commission->id . '" title="Edit"><i class="ti ti-edit"></i></a>';
                    $actions .= '<button type="button" class="btn btn-sm btn-danger delete-btn delete-commission" data-id="' . $commission->id . '" title="Delete"><i class="ti ti-trash"></i></button>';
                    $actions .= '</div>';

                    return $actions;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $inhouseProducts = InhouseProduct::where('is_active', true)->get();
        $commissions = $this->commissionRepository->all();

        return view('admin.ecard-seva-product-commissions.index', compact('inhouseProducts', 'commissions'));
    }

    /**
     * Show commission details for a specific inhouse product.
     */
    public function showDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'inhouse_product_id' => 'required|integer|exists:inhouse_products,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid product selected.',
            ], 400);
        }

        $inhouseProductId = $request->inhouse_product_id;
        $inhouseProduct = InhouseProduct::find($inhouseProductId);
        $commission = $this->commissionRepository->findByInhouseProductId($inhouseProductId);

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
                'inhouse_product' => $inhouseProduct,
                'commission' => $commission,
                'commission_levels' => $commissionLevels,
                'exists' => $commission ? true : false,
            ]
        ]);
    }

    /**
     * Store or update commission data.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'inhouse_product_id' => 'required|integer|exists:inhouse_products,id',
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
            $data = $request->only([
                'inhouse_product_id',
                'state_member_commission',
                'district_member_commission',
                'block_member_commission',
                'panchayat_member_commission',
                'village_member_commission',
                'customer_commission',
            ]);
            $data['is_active'] = $request->has('is_active');

            // Check if commission already exists for this inhouse product
            $existingCommission = $this->commissionRepository->findByInhouseProductId($request->inhouse_product_id);

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
        if (!$commission) {
            return response()->json([
                'success' => false,
                'message' => 'Commission not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $commission,
        ]);
    }

    /**
     * Update the specified commission in storage via AJAX.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'inhouse_product_id' => 'required|integer|exists:inhouse_products,id',
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

        // Check if another commission exists for the same inhouse product
        if ($this->commissionRepository->existsForInhouseProduct($request->inhouse_product_id, $id)) {
            return response()->json([
                'success' => false,
                'message' => 'Commission already exists for this product.',
            ], 409);
        }

        try {
            $data = $request->only([
                'inhouse_product_id',
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

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\ProductCategoryRepositoryInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\ProductStockTransferRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ProductStockTransferController extends Controller
{
    protected ProductStockTransferRepositoryInterface $transferRepository;

    protected ProductCategoryRepositoryInterface $categoryRepository;

    protected ProductRepositoryInterface $productRepository;

    public function __construct(
        ProductStockTransferRepositoryInterface $transferRepository,
        ProductCategoryRepositoryInterface $categoryRepository,
        ProductRepositoryInterface $productRepository
    ) {
        $this->transferRepository = $transferRepository;
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
    }

    /** Display Stock Transfer page with DataTable */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = $this->transferRepository->getForDataTable();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('product', function ($row) {
                    return $row->product_name ?? 'N/A';
                })
                ->addColumn('category', function ($row) {
                    return $row->category_name ?? 'N/A';
                })
                ->addColumn('from_level', function ($row) {
                    return $this->formatLevel('from', $row);
                })
                ->addColumn('to_level', function ($row) {
                    return $this->formatLevel('to', $row);
                })
                ->make(true);
        }

        $categories = $this->categoryRepository->getActive();

        return view('admin.stock-transfer.index', compact('categories'));
    }

    /** Store a new stock transfer */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_category_id' => 'required|exists:product_categories,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:1',
            'remarks' => 'nullable|string|max:1000',
            'from_level_type' => 'required|in:state,district,city,panchayat,village',
            'to_level_type' => 'required|in:state,district,city,panchayat,village',
            // From level specifics
            'from_state_id' => 'nullable|exists:states,id',
            'from_district_id' => 'nullable|exists:districts,id',
            'from_city_id' => 'nullable|exists:cities,id',
            'from_panchayat_name' => 'nullable|string|max:255',
            'from_village_name' => 'nullable|string|max:255',
            // To level specifics
            'to_state_id' => 'nullable|exists:states,id',
            'to_district_id' => 'nullable|exists:districts,id',
            'to_city_id' => 'nullable|exists:cities,id',
            'to_panchayat_name' => 'nullable|string|max:255',
            'to_village_name' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        // Enforce that corresponding identifiers are present based on level type
        $errors = [];
        $requireLevel = function (string $prefix, string $type) use (&$errors, $data) {
            switch ($type) {
                case 'state':
                    if (empty($data[$prefix.'_state_id'])) {
                        $errors[$prefix.'_state_id'] = ['State is required'];
                    }
                    break;
                case 'district':
                    if (empty($data[$prefix.'_district_id'])) {
                        $errors[$prefix.'_district_id'] = ['District is required'];
                    }
                    break;
                case 'city':
                    if (empty($data[$prefix.'_city_id'])) {
                        $errors[$prefix.'_city_id'] = ['City is required'];
                    }
                    break;
                case 'panchayat':
                    if (empty($data[$prefix.'_panchayat_name'])) {
                        $errors[$prefix + '_panchayat_name'] = ['Panchayat name is required'];
                    }
                    break;
                case 'village':
                    if (empty($data[$prefix + '_village_name'])) {
                        $errors[$prefix + '_village_name'] = ['Village name is required'];
                    }
                    break;
            }
        };

        $requireLevel('from', $data['from_level_type']);
        $requireLevel('to', $data['to_level_type']);
        if (! empty($errors)) {
            return response()->json(['success' => false, 'errors' => $errors], 422);
        }

        try {
            DB::beginTransaction();
            $transfer = $this->transferRepository->create($data);
            DB::commit();

            return response()->json(['success' => true, 'message' => 'Stock transferred successfully', 'data' => $transfer]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'Failed to transfer stock', 'error' => $e->getMessage()], 500);
        }
    }

    private function formatLevel(string $prefix, $row): string
    {
        $type = $prefix === 'from' ? $row->from_level_type : $row->to_level_type;
        switch ($type) {
            case 'state':
                $stateName = $prefix === 'from' ? ($row->from_state_id) : ($row->to_state_id);

                return ucfirst($type).' #'.($stateName ?? 'N/A');
            case 'district':
                $dist = $prefix === 'from' ? ($row->from_district_id) : ($row->to_district_id);

                return ucfirst($type).' #'.($dist ?? 'N/A');
            case 'city':
                $city = $prefix === 'from' ? ($row->from_city_id) : ($row->to_city_id);

                return ucfirst($type).' #'.($city ?? 'N/A');
            case 'panchayat':
                $name = $prefix === 'from' ? ($row->from_panchayat_name) : ($row->to_panchayat_name);

                return 'Panchayat '.($name ?? 'N/A');
            case 'village':
                $name = $prefix === 'from' ? ($row->from_village_name) : ($row->to_village_name);

                return 'Village '.($name ?? 'N/A');
            default:
                return 'N/A';
        }
    }

    /** Show Stock Transfer Report page */
    public function report()
    {
        return view('admin.stock-transfer.report');
    }

    /** Data endpoint for Stock Transfer Report */
    public function reportData(Request $request)
    {
        $filters = [
            'from_date' => $request->query('from_date'),
            'to_date' => $request->query('to_date'),
            'level' => $request->query('level'), // expected: state|district|city|panchayat|village
            'member_id' => $request->query('member_id'),
            'member_name' => $request->query('member_name'),
        ];

        $query = $this->transferRepository->getReportQuery($filters);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('product', function ($row) {
                return $row->product_name ?? 'N/A';
            })
            ->addColumn('category', function ($row) {
                return $row->category_name ?? 'N/A';
            })
            ->addColumn('from_level', function ($row) {
                return $this->formatLevel('from', $row);
            })
            ->addColumn('to_level', function ($row) {
                return $this->formatLevel('to', $row);
            })
            ->make(true);
    }
}

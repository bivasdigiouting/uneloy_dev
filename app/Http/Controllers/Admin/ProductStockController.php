<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\ProductCategoryRepositoryInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\ProductStockRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ProductStockController extends Controller
{
    protected ProductStockRepositoryInterface $stockRepository;

    protected ProductCategoryRepositoryInterface $categoryRepository;

    protected ProductRepositoryInterface $productRepository;

    public function __construct(
        ProductStockRepositoryInterface $stockRepository,
        ProductCategoryRepositoryInterface $categoryRepository,
        ProductRepositoryInterface $productRepository
    ) {
        $this->stockRepository = $stockRepository;
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
    }

    /** Display Add Product Stock page with DataTable */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = $this->stockRepository->getForDataTable();

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('type', function ($row) {
                    return ucfirst($row->type);
                })
                ->addColumn('product', function ($row) {
                    return $row->product_name ?? 'N/A';
                })
                ->addColumn('category', function ($row) {
                    return $row->category_name ?? 'N/A';
                })
                ->make(true);
        }

        $categories = $this->categoryRepository->getActive();

        return view('admin.product-stock.index', compact('categories'));
    }

    /** Fetch products for a given category (AJAX) */
    public function productsByCategory($categoryId)
    {
        $products = $this->productRepository->getByCategoryId((int) $categoryId);

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    /** Store a new stock transaction and update product stock */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_category_id' => 'required|exists:product_categories,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:1',
            'type' => 'required|in:in,out',
            'remarks' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        try {
            DB::beginTransaction();

            // Create transaction
            $transaction = $this->stockRepository->create([
                'product_category_id' => (int) $data['product_category_id'],
                'product_id' => (int) $data['product_id'],
                'quantity' => (float) $data['quantity'],
                'type' => $data['type'],
                'remarks' => $data['remarks'] ?? null,
            ]);

            // Update product stock
            $product = $this->productRepository->find((int) $data['product_id']);
            if (! $product) {
                DB::rollBack();

                return response()->json(['success' => false, 'message' => 'Product not found'], 404);
            }

            $currentStock = (float) ($product->stock ?? 0);
            $newStock = $currentStock;
            if ($data['type'] === 'in') {
                $newStock += (float) $data['quantity'];
            } else {
                $newStock -= (float) $data['quantity'];
                if ($newStock < 0) {
                    $newStock = 0; // Prevent negative stock; optionally enforce strict check
                }
            }

            $this->productRepository->update($product->id, ['stock' => $newStock]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Stock updated successfully',
                'data' => [
                    'transaction' => $transaction,
                    'new_stock' => $newStock,
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to update stock',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

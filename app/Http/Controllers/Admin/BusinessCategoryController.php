<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\BusinessCategoryRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class BusinessCategoryController extends Controller
{
    protected BusinessCategoryRepository $categoryRepository;

    public function __construct(BusinessCategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $categories = $this->categoryRepository->getForDataTables();

            return DataTables::of($categories)
                ->addIndexColumn()
                ->addColumn('status', function ($category) {
                    return $category->is_active
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                })
                ->addColumn('action', function ($category) {
                    $actions = '<div class="btn-group" role="group">';
                    $actions .= '<a href="'.route('admin.business-categories.show', $category->id).'" class="btn btn-sm btn-info" title="View"><i class="ti ti-eye"></i></a>';
                    $actions .= '<a href="'.route('admin.business-categories.edit', $category->id).'" class="btn btn-sm btn-primary" title="Edit"><i class="ti ti-edit"></i></a>';
                    $actions .= '<button type="button" class="btn btn-sm btn-danger" onclick="deleteCategory('.$category->id.')" title="Delete"><i class="ti ti-trash"></i></button>';
                    $actions .= '</div>';

                    return $actions;
                })
                ->editColumn('created_at', function ($category) {
                    return $category->created_at->format('d M Y, h:i A');
                })
                ->editColumn('description', function ($category) {
                    return $category->description ? \Str::limit($category->description, 50) : '-';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.business-categories.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.business-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'category_name' => 'required|string|max:255|unique:business_categories,category_name',
            'description' => 'nullable|string|max:1000',
            'slug' => 'nullable|string|max:255|unique:business_categories,slug',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->only(['category_name', 'description', 'slug', 'is_active', 'sort_order']);
            $data['is_active'] = true;
            $data['sort_order'] = $request->input('sort_order', 0);

            $this->categoryRepository->createCategory($data);

            return redirect()->route('admin.business-categories.index')
                ->with('success', 'Business category created successfully!');
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create business category. Please try again.')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): View
    {
        $category = $this->categoryRepository->findCategory($id);

        if (! $category) {
            abort(404, 'Business category not found');
        }

        return view('admin.business-categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $category = $this->categoryRepository->findCategory($id);

        if (! $category) {
            abort(404, 'Business category not found');
        }

        return view('admin.business-categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $category = $this->categoryRepository->findCategory($id);

        if (! $category) {
            abort(404, 'Business category not found');
        }

        $validator = Validator::make($request->all(), [
            'category_name' => 'required|string|max:255|unique:business_categories,category_name,'.$id,
            'description' => 'nullable|string|max:1000',
            'slug' => 'nullable|string|max:255|unique:business_categories,slug,'.$id,
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->only(['category_name', 'description', 'slug', 'is_active', 'sort_order']);
            $data['is_active'] = $request->has('is_active');
            $data['sort_order'] = $request->input('sort_order', 0);

            $this->categoryRepository->updateCategory($id, $data);

            return redirect()->route('admin.business-categories.index')
                ->with('success', 'Business category updated successfully!');
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update business category. Please try again.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $category = $this->categoryRepository->findCategory($id);

            if (! $category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Business category not found',
                ], 404);
            }

            $this->categoryRepository->deleteCategory($id);

            return response()->json([
                'success' => true,
                'message' => 'Business category deleted successfully!',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete business category. Please try again.',
            ], 500);
        }
    }

    /**
     * Toggle category status
     */
    public function toggleStatus(int $id): JsonResponse
    {
        try {
            $category = $this->categoryRepository->findCategory($id);

            if (! $category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Business category not found',
                ], 404);
            }

            $this->categoryRepository->toggleStatus($id);

            return response()->json([
                'success' => true,
                'message' => 'Category status updated successfully!',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update category status. Please try again.',
            ], 500);
        }
    }
}

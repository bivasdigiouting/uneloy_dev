<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InhouseProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class InhouseProductCategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = InhouseProductCategory::query()
                ->select(['id', 'code', 'name', 'slug', 'icon', 'display_order', 'status', 'created_at'])
                ->orderBy('display_order', 'asc')
                ->orderBy('id', 'desc');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('icon_display', function (InhouseProductCategory $category) {
                    if (! $category->icon) {
                        return '<span class="text-muted">No Icon</span>';
                    }

                    return '<img src="'.asset('storage/'.$category->icon).'" alt="Icon" style="width: 36px; height: 36px; object-fit: cover;" class="rounded">';
                })
                ->editColumn('status', function (InhouseProductCategory $category) {
                    return $category->status === 'active' ? 1 : 0;
                })
                ->editColumn('created_at', function (InhouseProductCategory $category) {
                    return $category->created_at?->format('Y-m-d H:i:s');
                })
                ->rawColumns(['icon_display'])
                ->make(true);
        }

        return view('admin.inhouse-product-categories.index');
    }

    public function create()
    {
        return view('admin.inhouse-product-categories.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => ['required', 'string', 'max:32', 'unique:inhouse_product_categories,code'],
            'name' => ['required', 'string', 'max:255', 'unique:inhouse_product_categories,name'],
            'display_order' => ['required', 'integer', 'min:0'],
            'icon' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $slug = Str::slug($request->input('name'));
        $slugBase = $slug;
        $i = 1;
        while (InhouseProductCategory::query()->where('slug', $slug)->exists()) {
            $i++;
            $slug = $slugBase.'-'.$i;
        }

        $data = [
            'code' => strtoupper(trim((string) $request->input('code'))),
            'name' => trim((string) $request->input('name')),
            'slug' => $slug,
            'display_order' => (int) $request->input('display_order'),
            'description' => $request->input('description'),
            'status' => $request->input('status'),
        ];

        if ($request->hasFile('icon')) {
            $data['icon'] = $request->file('icon')->store('inhouse-product-category-icons', 'public');
        }

        InhouseProductCategory::query()->create($data);

        return redirect()->route('admin.inhouse-product-categories.index')->with('success', 'Inhouse product category created successfully.');
    }

    public function show(int $id)
    {
        $category = InhouseProductCategory::query()->findOrFail($id);

        return view('admin.inhouse-product-categories.show', compact('category'));
    }

    public function edit(int $id)
    {
        $category = InhouseProductCategory::query()->findOrFail($id);

        return view('admin.inhouse-product-categories.edit', compact('category'));
    }

    public function update(Request $request, int $id)
    {
        $category = InhouseProductCategory::query()->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'code' => ['required', 'string', 'max:32', 'unique:inhouse_product_categories,code,'.$category->id],
            'name' => ['required', 'string', 'max:255', 'unique:inhouse_product_categories,name,'.$category->id],
            'display_order' => ['required', 'integer', 'min:0'],
            'icon' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = [
            'code' => strtoupper(trim((string) $request->input('code'))),
            'name' => trim((string) $request->input('name')),
            'display_order' => (int) $request->input('display_order'),
            'description' => $request->input('description'),
            'status' => $request->input('status'),
        ];

        if ($category->name !== $data['name']) {
            $slug = Str::slug($data['name']);
            $slugBase = $slug;
            $i = 1;
            while (
                InhouseProductCategory::query()
                    ->where('slug', $slug)
                    ->where('id', '!=', $category->id)
                    ->exists()
            ) {
                $i++;
                $slug = $slugBase.'-'.$i;
            }
            $data['slug'] = $slug;
        }

        if ($request->hasFile('icon')) {
            if ($category->icon && Storage::disk('public')->exists($category->icon)) {
                Storage::disk('public')->delete($category->icon);
            }
            $data['icon'] = $request->file('icon')->store('inhouse-product-category-icons', 'public');
        }

        $category->update($data);

        return redirect()->route('admin.inhouse-product-categories.index')->with('success', 'Inhouse product category updated successfully.');
    }

    public function destroy(int $id)
    {
        $category = InhouseProductCategory::query()->findOrFail($id);
        if ($category->icon && Storage::disk('public')->exists($category->icon)) {
            Storage::disk('public')->delete($category->icon);
        }
        $category->delete();

        return response()->json(['success' => true]);
    }

    public function toggleStatus(int $id)
    {
        $category = InhouseProductCategory::query()->findOrFail($id);
        $category->status = $category->status === 'active' ? 'inactive' : 'active';
        $category->save();

        return response()->json([
            'success' => true,
            'status' => $category->status === 'active' ? 1 : 0,
        ]);
    }

    public function getActiveList()
    {
        $data = InhouseProductCategory::query()
            ->where('status', 'active')
            ->orderBy('display_order', 'asc')
            ->orderBy('name', 'asc')
            ->get(['id', 'code', 'name']);

        return response()->json(['success' => true, 'data' => $data]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class CmsPageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $pages = CmsPage::select(['id', 'title', 'slug', 'status', 'created_at']);

            return DataTables::of($pages)
                ->addIndexColumn()
                ->addColumn('status', function ($page) {
                    return $page->status
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                })
                ->addColumn('action', function ($page) {
                    $actions = '<div class="btn-group" role="group">';
                    $actions .= '<a href="'.route('admin.cms-pages.edit', $page->id).'" class="btn btn-sm btn-primary" title="Edit"><i class="ti ti-edit"></i></a>';
                    // We might not want to delete these core pages easily, but I'll add the button.
                    $actions .= '<button type="button" class="btn btn-sm btn-danger" onclick="deletePage('.$page->id.')" title="Delete"><i class="ti ti-trash"></i></button>';
                    $actions .= '</div>';

                    return $actions;
                })
                ->editColumn('created_at', function ($page) {
                    return $page->created_at->format('d M Y, h:i A');
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.cms-pages.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.cms-pages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:cms_pages,slug',
            'content' => 'required|string',
            'status' => 'boolean',
        ]);

        $slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->title);
        
        // Ensure slug is unique if generated from title
        if (!$request->slug && CmsPage::where('slug', $slug)->exists()) {
             $slug = $slug . '-' . time();
        }

        CmsPage::create([
            'title' => $request->title,
            'slug' => $slug,
            'content' => $request->content,
            'status' => $request->has('status') ? $request->status : 0,
        ]);

        return redirect()->route('admin.cms-pages.index')
            ->with('success', 'Page created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $page = CmsPage::findOrFail($id);
        return view('admin.cms-pages.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $page = CmsPage::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:cms_pages,slug,' . $page->id,
            'content' => 'required|string',
            'status' => 'boolean',
        ]);

        $slug = $request->slug ? Str::slug($request->slug) : $page->slug;

        $page->update([
            'title' => $request->title,
            'slug' => $slug,
            'content' => $request->content,
            'status' => $request->has('status') ? $request->status : 0,
        ]);

        return redirect()->route('admin.cms-pages.index')
            ->with('success', 'Page updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $page = CmsPage::findOrFail($id);
        $page->delete();

        return response()->json(['success' => 'Page deleted successfully.']);
    }
}

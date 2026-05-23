<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $news = News::select(['id', 'title', 'slug', 'is_published', 'published_at', 'created_at']);

            return DataTables::of($news)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    return $row->is_published
                        ? '<span class="badge bg-success">Published</span>'
                        : '<span class="badge bg-warning text-dark">Draft</span>';
                })
                ->editColumn('published_at', function ($row) {
                    return $row->published_at ? $row->published_at->format('d M Y, h:i A') : '-';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d M Y, h:i A') : '-';
                })
                ->addColumn('action', function ($row) {
                    $actions = '<div class="btn-group" role="group">';
                    $actions .= '<a href="'.route('admin.news.edit', $row->id).'" class="btn btn-sm btn-primary" title="Edit"><i class="ti ti-edit"></i></a>';
                    $actions .= '<button type="button" class="btn btn-sm btn-danger" onclick="deleteNews('.$row->id.')" title="Delete"><i class="ti ti-trash"></i></button>';
                    $actions .= '</div>';

                    return $actions;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.news.index');
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:news,slug',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'is_published' => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ]);

        $slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->title);
        if (! $request->slug && News::where('slug', $slug)->exists()) {
            $slug = $slug.'-'.time();
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('news', 'public');
        }

        $isPublished = (bool) $request->boolean('is_published');
        $publishedAt = $request->input('published_at');
        if ($isPublished && ! $publishedAt) {
            $publishedAt = now();
        }

        News::create([
            'title' => $request->title,
            'slug' => $slug,
            'image' => $imagePath,
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'is_published' => $isPublished,
            'published_at' => $publishedAt,
        ]);

        return redirect()->route('admin.news.index')->with('success', 'News created successfully.');
    }

    public function edit($id)
    {
        $news = News::findOrFail($id);

        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:news,slug,'.$news->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'is_published' => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ]);

        $slug = $request->slug ? Str::slug($request->slug) : $news->slug;

        $imagePath = $news->image;
        if ($request->hasFile('image')) {
            if ($news->image && Storage::disk('public')->exists($news->image)) {
                Storage::disk('public')->delete($news->image);
            }
            $imagePath = $request->file('image')->store('news', 'public');
        }

        $isPublished = (bool) $request->boolean('is_published');
        $publishedAt = $request->input('published_at');
        if ($isPublished && ! $publishedAt) {
            $publishedAt = $news->published_at ?: now();
        }
        if (! $isPublished) {
            $publishedAt = null;
        }

        $news->update([
            'title' => $request->title,
            'slug' => $slug,
            'image' => $imagePath,
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'is_published' => $isPublished,
            'published_at' => $publishedAt,
        ]);

        return redirect()->route('admin.news.index')->with('success', 'News updated successfully.');
    }

    public function destroy($id)
    {
        $news = News::findOrFail($id);

        if ($news->image && Storage::disk('public')->exists($news->image)) {
            Storage::disk('public')->delete($news->image);
        }

        $news->delete();

        return response()->json(['success' => 'News deleted successfully.']);
    }
}

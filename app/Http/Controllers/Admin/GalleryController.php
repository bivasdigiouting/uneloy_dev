<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryAlbum;
use App\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $albums = GalleryAlbum::query()
                ->select(['id', 'title', 'slug', 'cover_image', 'sort_order', 'is_active', 'created_at'])
                ->withCount(['images' => function ($q) {
                    $q->where('is_active', true);
                }])
                ->orderBy('sort_order')
                ->orderByDesc('created_at');

            return DataTables::of($albums)
                ->addIndexColumn()
                ->addColumn('preview', function ($row) {
                    $src = $row->cover_image ? asset('storage/'.$row->cover_image) : asset('frontend-assets/design_img/inner-banner.jpg');

                    return '<img src="'.$src.'" style="width:70px;height:50px;object-fit:cover;border-radius:6px;border:1px solid #ddd;">';
                })
                ->addColumn('status', function ($row) {
                    return $row->is_active
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-secondary">Inactive</span>';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d M Y, h:i A') : '-';
                })
                ->addColumn('action', function ($row) {
                    $actions = '<div class="btn-group" role="group">';
                    $actions .= '<a href="'.route('admin.galleries.edit', $row->id).'" class="btn btn-sm btn-primary" title="Manage"><i class="ti ti-edit"></i></a>';
                    $actions .= '<button type="button" class="btn btn-sm btn-danger" onclick="deleteGallery('.$row->id.')" title="Delete"><i class="ti ti-trash"></i></button>';
                    $actions .= '</div>';

                    return $actions;
                })
                ->rawColumns(['preview', 'status', 'action'])
                ->make(true);
        }

        return view('admin.gallery.index');
    }

    public function create()
    {
        return view('admin.gallery.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:gallery_albums,slug',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'images' => 'required|array|min:1',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        $isActive = (bool) $request->boolean('is_active', true);
        $sortOrder = (int) ($request->input('sort_order') ?? 0);

        $slug = $request->input('slug') ? Str::slug($request->input('slug')) : Str::slug($request->input('title'));
        if ($slug === '') {
            $slug = 'gallery';
        }
        if (GalleryAlbum::where('slug', $slug)->exists()) {
            $slug = $slug.'-'.time();
        }

        $coverImage = null;
        $uploaded = $request->file('images', []);
        if (! empty($uploaded)) {
            $coverImage = $uploaded[0]->store('gallery', 'public');
        }

        $album = GalleryAlbum::create([
            'title' => $request->input('title'),
            'slug' => $slug,
            'cover_image' => $coverImage,
            'sort_order' => $sortOrder,
            'is_active' => $isActive,
        ]);

        if (! empty($uploaded)) {
            foreach ($uploaded as $idx => $file) {
                if ($idx === 0) {
                    $path = $coverImage;
                } else {
                    $path = $file->store('gallery', 'public');
                }

                GalleryImage::create([
                    'gallery_album_id' => $album->id,
                    'title' => null,
                    'image' => $path,
                    'sort_order' => $idx,
                    'is_active' => true,
                ]);
            }
        }

        return redirect()->route('admin.galleries.index')->with('success', 'Gallery created successfully.');
    }

    public function edit($id)
    {
        $album = GalleryAlbum::with(['images' => function ($q) {
            $q->orderBy('sort_order')->orderBy('id');
        }])->findOrFail($id);

        return view('admin.gallery.edit', compact('album'));
    }

    public function update(Request $request, $id)
    {
        $album = GalleryAlbum::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:gallery_albums,slug,'.$album->id,
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'images' => 'nullable|array',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        $slug = $request->input('slug') ? Str::slug($request->input('slug')) : Str::slug($request->input('title'));
        if ($slug === '') {
            $slug = $album->slug;
        }

        $data = $request->only(['title', 'sort_order']);
        $data['slug'] = $slug;
        $data['is_active'] = (bool) $request->boolean('is_active');

        if ($request->hasFile('cover_image')) {
            if ($album->cover_image && Storage::disk('public')->exists($album->cover_image)) {
                Storage::disk('public')->delete($album->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('gallery', 'public');
        }

        $album->update($data);

        $uploaded = $request->file('images', []);
        if (! empty($uploaded)) {
            $startSort = (int) GalleryImage::where('gallery_album_id', $album->id)->max('sort_order');
            $startSort = $startSort >= 0 ? $startSort + 1 : 0;

            foreach ($uploaded as $file) {
                $path = $file->store('gallery', 'public');
                GalleryImage::create([
                    'gallery_album_id' => $album->id,
                    'title' => null,
                    'image' => $path,
                    'sort_order' => $startSort,
                    'is_active' => true,
                ]);
                $startSort++;
            }
        }

        return redirect()->route('admin.galleries.edit', $album->id)->with('success', 'Gallery updated successfully.');
    }

    public function destroy($id)
    {
        $album = GalleryAlbum::with('images')->findOrFail($id);

        foreach ($album->images as $img) {
            if ($img->image && Storage::disk('public')->exists($img->image)) {
                Storage::disk('public')->delete($img->image);
            }
        }

        if ($album->cover_image && Storage::disk('public')->exists($album->cover_image)) {
            Storage::disk('public')->delete($album->cover_image);
        }

        $album->images()->delete();
        $album->delete();

        return response()->json(['success' => 'Gallery deleted successfully.']);
    }

    public function destroyImage($id)
    {
        $img = GalleryImage::findOrFail($id);
        $album = $img->album;

        if ($img->image && Storage::disk('public')->exists($img->image)) {
            Storage::disk('public')->delete($img->image);
        }

        $img->delete();

        if ($album && $album->cover_image === $img->image) {
            $nextCover = GalleryImage::where('gallery_album_id', $album->id)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->value('image');

            $album->update([
                'cover_image' => $nextCover,
            ]);
        }

        return response()->json(['success' => 'Image deleted successfully.']);
    }
}

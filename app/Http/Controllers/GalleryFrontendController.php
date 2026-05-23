<?php

namespace App\Http\Controllers;

use App\Models\GalleryAlbum;

class GalleryFrontendController extends Controller
{
    public function index()
    {
        $albums = GalleryAlbum::query()
            ->where('is_active', true)
            ->withCount(['images' => function ($q) {
                $q->where('is_active', true);
            }])
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->get();

        return view('frontend.gallery', compact('albums'));
    }

    public function show($slug)
    {
        $album = GalleryAlbum::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $images = $album->images()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('frontend.gallery-show', compact('album', 'images'));
    }
}

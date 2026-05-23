<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class FrontendNewsController extends Controller
{
    public function index(Request $request)
    {
        $newsItems = News::query()
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->orderByDesc('published_at')
            ->paginate(9);

        return view('frontend.news.index', compact('newsItems'));
    }

    public function show($slug)
    {
        $news = News::query()
            ->where('slug', $slug)
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->firstOrFail();

        $latestNews = News::query()
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('id', '!=', $news->id)
            ->orderByDesc('published_at')
            ->limit(6)
            ->get();

        return view('frontend.news.show', compact('news', 'latestNews'));
    }
}

@extends('layouts.public')

@section('content')
<main class="container my-4" style="min-height: 60vh;">
    <div class="row">
        <div class="col-12 mb-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('frontend.news.index') }}">News</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ \Illuminate\Support\Str::limit($news->title, 60) }}</li>
                </ol>
            </nav>
        </div>

        <div class="col-lg-8">
            <article class="card border-0 shadow-sm">
                @if($news->image_url)
                    <img src="{{ $news->image_url }}" class="card-img-top" alt="{{ $news->title }}" style="max-height: 360px; object-fit: cover;">
                @endif
                <div class="card-body">
                    <div class="text-muted small mb-2">
                        {{ $news->published_at ? $news->published_at->format('d M Y, h:i A') : '' }}
                    </div>
                    <h1 class="h3 mb-3">{{ $news->title }}</h1>
                    <div class="news-content">
                        {!! $news->content !!}
                    </div>
                </div>
            </article>
        </div>

        <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="mb-3">Latest News</h5>
                    @forelse($latestNews as $item)
                        <div class="d-flex mb-3">
                            @if($item->image_url)
                                <a href="{{ route('frontend.news.show', $item->slug) }}" class="me-2 flex-shrink-0">
                                    <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="rounded" style="width: 72px; height: 54px; object-fit: cover;">
                                </a>
                            @endif
                            <div>
                                <div class="text-muted small">{{ $item->published_at ? $item->published_at->format('d M Y') : '' }}</div>
                                <a href="{{ route('frontend.news.show', $item->slug) }}" class="text-decoration-none">
                                    {{ \Illuminate\Support\Str::limit($item->title, 60) }}
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No other news available.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</main>
@endsection


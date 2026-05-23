@extends('layouts.public')

@section('content')
<main class="container my-4" style="min-height: 60vh;">
    <div class="row">
        <div class="col-12">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
                <div>
                    <h1 class="mb-1">News</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">News</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        @forelse($newsItems as $item)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    @if($item->image_url)
                        <a href="{{ route('frontend.news.show', $item->slug) }}">
                            <img src="{{ $item->image_url }}" class="card-img-top" alt="{{ $item->title }}" style="height: 200px; object-fit: cover;">
                        </a>
                    @endif
                    <div class="card-body d-flex flex-column">
                        <div class="mb-2 text-muted small">
                            {{ $item->published_at ? $item->published_at->format('d M Y') : '' }}
                        </div>
                        <h5 class="card-title">
                            <a href="{{ route('frontend.news.show', $item->slug) }}" class="text-decoration-none text-dark">
                                {{ $item->title }}
                            </a>
                        </h5>
                        <p class="card-text text-muted">
                            {{ \Illuminate\Support\Str::limit(strip_tags($item->excerpt ?: $item->content), 140) }}
                        </p>
                        <div class="mt-auto">
                            <a href="{{ route('frontend.news.show', $item->slug) }}" class="btn btn-outline-primary btn-sm">
                                Read More
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    No news published yet.
                </div>
            </div>
        @endforelse

        @if($newsItems->hasPages())
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    {{ $newsItems->links() }}
                </div>
            </div>
        @endif
    </div>
</main>
@endsection


@extends('layouts.public')

@section('content')
<main class="container my-4" style="min-height: 60vh;">
    <div class="row">
        <div class="col-12 mb-3">
            <h1 class="mb-1">Gallery</h1>
            <p class="text-muted mb-0">Select a gallery title to view photos.</p>
        </div>

        @forelse($albums as $album)
            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-3">
                <a href="{{ route('frontend.gallery.show', $album->slug) }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm" style="overflow:hidden;">
                        @php
                            $cover = $album->cover_image_url ?: asset('frontend-assets/design_img/inner-banner.jpg');
                        @endphp
                        <img src="{{ $cover }}" alt="{{ $album->title }}" style="width:100%;height:200px;object-fit:cover;">
                        <div class="card-body" style="padding:10px;">
                            <div class="text-truncate" style="font-weight:600;">
                                {{ $album->title }}
                            </div>
                            <div class="text-muted" style="font-size:12px;">
                                {{ $album->images_count }} {{ $album->images_count == 1 ? 'Photo' : 'Photos' }}
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">No gallery albums available yet.</div>
            </div>
        @endforelse
    </div>
</main>
@endsection

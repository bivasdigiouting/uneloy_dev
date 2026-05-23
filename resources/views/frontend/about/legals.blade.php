@extends('layouts.public')

@section('content')
<main class="container my-5" style="min-height: 50vh;">
    @if($legal)
        <div class="row align-items-center mb-5 mt-4">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h1 class="display-6 fw-bold text-primary mb-3">{{ $legal->text_header }}</h1>
                <div class="lead text-muted fs-5">
                    {!! $legal->text_description !!}
                </div>
            </div>
            <div class="col-lg-6 text-center">
                @if($legal->image_url)
                    <img src="{{ $legal->image_url }}" alt="{{ $legal->text_header }}" class="img-fluid rounded shadow-sm" style="max-height: 400px; object-fit: cover; width: 100%;">
                @else
                    <div class="rounded bg-light d-flex align-items-center justify-content-center mx-auto text-secondary shadow-sm" style="height: 300px; width: 100%;">
                        <div class="text-center">
                            <i class="ti ti-photo" style="font-size: 4rem;"></i>
                            <p class="mt-2">No Image Available</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        
        @if($legal->footer_short_description)
            <div class="row">
                <div class="col-12 mt-4 p-4 bg-light rounded text-center">
                    <div class="text-secondary fs-5">
                        {!! $legal->footer_short_description !!}
                    </div>
                </div>
            </div>
        @endif
    @else
        <div class="text-center py-5 mt-5">
            <h1 class="display-5 fw-bold text-primary">Legals</h1>
            <p class="text-muted fs-5 mt-3">Content is currently being updated. Please check back later.</p>
        </div>
    @endif
</main>
@endsection

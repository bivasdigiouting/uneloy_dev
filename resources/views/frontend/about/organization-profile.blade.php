@extends('layouts.public')

@section('content')
<main class="container my-4" style="min-height: 50vh;">
    <div class="row">
        <div class="col-md-7">
            <h1 class="mb-3">Organization Profile</h1>
            @if(isset($aboutUs) && $aboutUs)
                @if($aboutUs->text_header)
                    <h4 class="text-primary">{{ $aboutUs->text_header }}</h4>
                @endif
                <div class="mt-3">
                    {!! $aboutUs->text_description !!}
                </div>
            @else
                <p class="text-muted">Content will be available soon.</p>
            @endif
        </div>
        <div class="col-md-5">
            @if(isset($aboutUs) && $aboutUs && $aboutUs->image_url)
                <img src="{{ $aboutUs->image_url }}" alt="About Us" class="img-fluid rounded shadow-sm">
            @endif
            @if(isset($aboutUs) && $aboutUs && $aboutUs->footer_short_description)
                <div class="card mt-3">
                    <div class="card-body">
                        <p class="mb-0">{!! $aboutUs->footer_short_description !!}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</main>
@endsection
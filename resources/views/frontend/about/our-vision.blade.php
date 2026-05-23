@extends('layouts.public')

@section('content')
<main class="container my-4" style="min-height: 50vh;">
    <div class="row">
        <div class="col-md-7">
            <h1 class="mb-3">Our Vision</h1>
            @if(isset($ourVision) && $ourVision)
                @if($ourVision->text_header)
                    <h4 class="text-primary">{{ $ourVision->text_header }}</h4>
                @endif
                <div class="mt-3">
                    {!! $ourVision->text_description !!}
                </div>
            @else
                <p class="text-muted">Content will be available soon.</p>
            @endif
        </div>
        <div class="col-md-5">
            @if(isset($ourVision) && $ourVision && $ourVision->image_url)
                <img src="{{ $ourVision->image_url }}" alt="Our Vision" class="img-fluid rounded shadow-sm">
            @endif
            @if(isset($ourVision) && $ourVision && $ourVision->footer_short_description)
                <div class="card mt-3">
                    <div class="card-body">
                        <p class="mb-0">{!! $ourVision->footer_short_description !!}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</main>
@endsection

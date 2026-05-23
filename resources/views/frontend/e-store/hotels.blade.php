@extends('layouts.public')

@section('content')
<main class="container my-4" style="min-height: 50vh;">
    <div class="row">
        <div class="col-md-7">
            <h1 class="mb-3">Hotels</h1>
            @if(isset($hotel) && $hotel)
                @if($hotel->text_header)
                    <h4 class="text-primary">{{ $hotel->text_header }}</h4>
                @endif
                <div class="mt-3">
                    {!! $hotel->text_description !!}
                </div>
            @else
                <p class="text-muted">Hotel services will be available soon.</p>
            @endif
        </div>
        <div class="col-md-5">
            @if(isset($hotel) && $hotel && $hotel->image)
                <img src="{{ $hotel->image_url }}" alt="Hotels" class="img-fluid rounded shadow-sm">
            @endif
            @if(isset($hotel) && $hotel && $hotel->footer_short_description)
                <div class="card mt-3">
                    <div class="card-body">
                        <p class="mb-0">{!! $hotel->footer_short_description !!}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</main>
@endsection

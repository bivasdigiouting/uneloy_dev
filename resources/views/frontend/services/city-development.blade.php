@extends('layouts.public')

@section('content')
<main class="container my-4" style="min-height: 50vh;">
    <div class="row">
        <div class="col-md-7">
            <h1 class="mb-3">City Development</h1>
            @if(isset($cityDevelopment) && $cityDevelopment)
                @if($cityDevelopment->text_header)
                    <h4 class="text-primary">{{ $cityDevelopment->text_header }}</h4>
                @endif
                <div class="mt-3">
                    {!! $cityDevelopment->text_description !!}
                </div>
            @else
                <p class="text-muted">Content will be available soon.</p>
            @endif
        </div>
        <div class="col-md-5">
            @if(isset($cityDevelopment) && $cityDevelopment && $cityDevelopment->image)
                <img src="{{ $cityDevelopment->image_url }}" alt="City Development" class="img-fluid rounded shadow-sm">
            @endif
            @if(isset($cityDevelopment) && $cityDevelopment && $cityDevelopment->footer_short_description)
                <div class="card mt-3">
                    <div class="card-body">
                        <p class="mb-0">{!! $cityDevelopment->footer_short_description !!}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</main>
@endsection

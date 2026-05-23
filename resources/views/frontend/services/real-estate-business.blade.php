@extends('layouts.public')

@section('content')
<main class="container my-4" style="min-height: 50vh;">
    <div class="row">
        <div class="col-md-7">
            <h1 class="mb-3">Real Estate Business</h1>
            @if(isset($realEstateBusiness) && $realEstateBusiness)
                @if($realEstateBusiness->text_header)
                    <h4 class="text-primary">{{ $realEstateBusiness->text_header }}</h4>
                @endif
                <div class="mt-3">
                    {!! $realEstateBusiness->text_description !!}
                </div>
            @else
                <p class="text-muted">Content will be available soon.</p>
            @endif
        </div>
        <div class="col-md-5">
            @if(isset($realEstateBusiness) && $realEstateBusiness && $realEstateBusiness->image)
                <img src="{{ $realEstateBusiness->image_url }}" alt="Real Estate Business" class="img-fluid rounded shadow-sm">
            @endif
            @if(isset($realEstateBusiness) && $realEstateBusiness && $realEstateBusiness->footer_short_description)
                <div class="card mt-3">
                    <div class="card-body">
                        <p class="mb-0">{!! $realEstateBusiness->footer_short_description !!}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</main>
@endsection

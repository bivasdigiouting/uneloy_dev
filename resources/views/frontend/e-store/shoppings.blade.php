@extends('layouts.public')

@section('content')
<main class="container my-4" style="min-height: 50vh;">
    <div class="row">
        <div class="col-md-7">
            <h1 class="mb-3">Shoppings</h1>
            @if(isset($shopping) && $shopping)
                @if($shopping->text_header)
                    <h4 class="text-primary">{{ $shopping->text_header }}</h4>
                @endif
                <div class="mt-3">
                    {!! $shopping->text_description !!}
                </div>
            @else
                <p class="text-muted">Shopping services will be available soon.</p>
            @endif
        </div>
        <div class="col-md-5">
            @if(isset($shopping) && $shopping && $shopping->image)
                <img src="{{ $shopping->image_url }}" alt="Shoppings" class="img-fluid rounded shadow-sm">
            @endif
            @if(isset($shopping) && $shopping && $shopping->footer_short_description)
                <div class="card mt-3">
                    <div class="card-body">
                        <p class="mb-0">{!! $shopping->footer_short_description !!}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</main>
@endsection


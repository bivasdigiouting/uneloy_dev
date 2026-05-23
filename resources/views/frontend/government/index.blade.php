@extends('layouts.public')

@section('content')
<main class="container my-4" style="min-height: 50vh;">
    <div class="row">
        <div class="col-md-7">
            <h1 class="mb-3">Government</h1>
            @if(isset($government) && $government)
                @if($government->text_header)
                    <h4 class="text-primary">{{ $government->text_header }}</h4>
                @endif
                <div class="mt-3">
                    {!! $government->text_description !!}
                </div>
            @else
                <p class="text-muted">Content will be available soon.</p>
            @endif
        </div>
        <div class="col-md-5">
            @if(isset($government) && $government && $government->image_url)
                <img src="{{ $government->image_url }}" alt="Government" class="img-fluid rounded shadow-sm">
            @endif
            @if(isset($government) && $government && $government->footer_short_description)
                <div class="card mt-3">
                    <div class="card-body">
                        <p class="mb-0">{!! $government->footer_short_description !!}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</main>
@endsection

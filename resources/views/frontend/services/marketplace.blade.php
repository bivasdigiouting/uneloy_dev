@extends('layouts.public')

@section('content')
<main class="container my-4" style="min-height: 50vh;">
    <div class="row">
        <div class="col-md-7">
            <h1 class="mb-3">Marketplace</h1>
            @if(isset($marketplace) && $marketplace)
                @if($marketplace->text_header)
                    <h4 class="text-primary">{{ $marketplace->text_header }}</h4>
                @endif
                <div class="mt-3">
                    {!! $marketplace->text_description !!}
                </div>
            @else
                <p class="text-muted">Content will be available soon.</p>
            @endif
        </div>
        <div class="col-md-5">
            @if(isset($marketplace) && $marketplace && $marketplace->image)
                <img src="{{ $marketplace->image_url }}" alt="Marketplace" class="img-fluid rounded shadow-sm">
            @endif
            @if(isset($marketplace) && $marketplace && $marketplace->footer_short_description)
                <div class="card mt-3">
                    <div class="card-body">
                        <p class="mb-0">{!! $marketplace->footer_short_description !!}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</main>
@endsection

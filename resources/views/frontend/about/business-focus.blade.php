@extends('layouts.public')

@section('content')
<main class="container my-4" style="min-height: 50vh;">
    <div class="row">
        <div class="col-md-7">
            <h1 class="mb-3">Business Focus</h1>
            @if(isset($businessFocus) && $businessFocus)
                @if($businessFocus->text_header)
                    <h4 class="text-primary">{{ $businessFocus->text_header }}</h4>
                @endif
                <div class="mt-3">
                    {!! $businessFocus->text_description !!}
                </div>
            @else
                <p class="text-muted">Content will be available soon.</p>
            @endif
        </div>
        <div class="col-md-5">
            @if(isset($businessFocus) && $businessFocus && $businessFocus->image_url)
                <img src="{{ $businessFocus->image_url }}" alt="Business Focus" class="img-fluid rounded shadow-sm">
            @endif
            @if(isset($businessFocus) && $businessFocus && $businessFocus->footer_short_description)
                <div class="card mt-3">
                    <div class="card-body">
                        <p class="mb-0">{!! $businessFocus->footer_short_description !!}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</main>
@endsection

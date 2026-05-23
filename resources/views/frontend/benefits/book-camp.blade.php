@extends('layouts.public')

@section('content')
<main class="container my-4" style="min-height: 50vh;">
    <div class="row">
        <div class="col-md-7">
            <h1 class="mb-3">Book Camp</h1>
            @if(isset($bookCamp) && $bookCamp)
                @if($bookCamp->text_header)
                    <h4 class="text-primary">{{ $bookCamp->text_header }}</h4>
                @endif
                <div class="mt-3">
                    {!! $bookCamp->text_description !!}
                </div>
            @else
                <p class="text-muted">Content will be available soon.</p>
            @endif
        </div>
        <div class="col-md-5">
            @if(isset($bookCamp) && $bookCamp && $bookCamp->image_url)
                <img src="{{ $bookCamp->image_url }}" alt="Book Camp" class="img-fluid rounded shadow-sm">
            @endif
            @if(isset($bookCamp) && $bookCamp && $bookCamp->footer_short_description)
                <div class="card mt-3">
                    <div class="card-body">
                        <p class="mb-0">{!! $bookCamp->footer_short_description !!}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</main>
@endsection

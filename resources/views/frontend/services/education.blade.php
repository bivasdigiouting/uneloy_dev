@extends('layouts.public')

@section('content')
<main class="container my-4" style="min-height: 50vh;">
    <div class="row">
        <div class="col-md-7">
            <h1 class="mb-3">Education</h1>
            @if(isset($education) && $education)
                @if($education->text_header)
                    <h4 class="text-primary">{{ $education->text_header }}</h4>
                @endif
                <div class="mt-3">
                    {!! $education->text_description !!}
                </div>
            @else
                <p class="text-muted">Content will be available soon.</p>
            @endif
        </div>
        <div class="col-md-5">
            @if(isset($education) && $education && $education->image)
                <img src="{{ $education->image_url }}" alt="Education Service" class="img-fluid rounded shadow-sm">
            @endif
            @if(isset($education) && $education && $education->footer_short_description)
                <div class="card mt-3">
                    <div class="card-body">
                        <p class="mb-0">{!! $education->footer_short_description !!}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</main>
@endsection

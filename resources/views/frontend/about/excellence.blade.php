@extends('layouts.public')

@section('content')
<main class="container my-4" style="min-height: 50vh;">
    <div class="row">
        <div class="col-md-7">
            <h1 class="mb-3">Excellence</h1>
            @if(isset($excellence) && $excellence)
                @if($excellence->text_header)
                    <h4 class="text-primary">{{ $excellence->text_header }}</h4>
                @endif
                <div class="mt-3">
                    {!! $excellence->text_description !!}
                </div>
            @else
                <p class="text-muted">Content will be available soon.</p>
            @endif
        </div>
        <div class="col-md-5">
            @if(isset($excellence) && $excellence && $excellence->image_url)
                <img src="{{ $excellence->image_url }}" alt="Excellence" class="img-fluid rounded shadow-sm">
            @endif
            @if(isset($excellence) && $excellence && $excellence->footer_short_description)
                <div class="card mt-3">
                    <div class="card-body">
                        <p class="mb-0">{!! $excellence->footer_short_description !!}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</main>
@endsection

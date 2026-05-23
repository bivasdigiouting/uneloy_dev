@extends('layouts.public')

@section('content')
<main class="container my-4" style="min-height: 50vh;">
    <div class="row">
        <div class="col-md-7">
            <h1 class="mb-3">Blood Donate</h1>
            @if(isset($bloodDonate) && $bloodDonate)
                @if($bloodDonate->text_header)
                    <h4 class="text-primary">{{ $bloodDonate->text_header }}</h4>
                @endif
                <div class="mt-3">
                    {!! $bloodDonate->text_description !!}
                </div>
            @else
                <p class="text-muted">Content will be available soon.</p>
            @endif
        </div>
        <div class="col-md-5">
            @if(isset($bloodDonate) && $bloodDonate && $bloodDonate->image_url)
                <img src="{{ $bloodDonate->image_url }}" alt="Blood Donate" class="img-fluid rounded shadow-sm">
            @endif
            @if(isset($bloodDonate) && $bloodDonate && $bloodDonate->footer_short_description)
                <div class="card mt-3">
                    <div class="card-body">
                        <p class="mb-0">{!! $bloodDonate->footer_short_description !!}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</main>
@endsection

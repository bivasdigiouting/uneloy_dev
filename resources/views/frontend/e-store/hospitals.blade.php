@extends('layouts.public')

@section('content')
<main class="container my-4" style="min-height: 50vh;">
    <div class="row">
        <div class="col-md-7">
            <h1 class="mb-3">Hospitals</h1>
            @if(isset($hospital) && $hospital)
                @if($hospital->text_header)
                    <h4 class="text-primary">{{ $hospital->text_header }}</h4>
                @endif
                <div class="mt-3">
                    {!! $hospital->text_description !!}
                </div>
            @else
                <p class="text-muted">Hospital services will be available soon.</p>
            @endif
        </div>
        <div class="col-md-5">
            @if(isset($hospital) && $hospital && $hospital->image)
                <img src="{{ $hospital->image_url }}" alt="Hospitals" class="img-fluid rounded shadow-sm">
            @endif
            @if(isset($hospital) && $hospital && $hospital->footer_short_description)
                <div class="card mt-3">
                    <div class="card-body">
                        <p class="mb-0">{!! $hospital->footer_short_description !!}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</main>
@endsection


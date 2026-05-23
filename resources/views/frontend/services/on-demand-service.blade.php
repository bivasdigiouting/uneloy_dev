@extends('layouts.public')

@section('content')
<main class="container my-4" style="min-height: 50vh;">
    <div class="row">
        <div class="col-md-7">
            <h1 class="mb-3">On Demand Service</h1>
            @if(isset($onDemandService) && $onDemandService)
                @if($onDemandService->text_header)
                    <h4 class="text-primary">{{ $onDemandService->text_header }}</h4>
                @endif
                <div class="mt-3">
                    {!! $onDemandService->text_description !!}
                </div>
            @else
                <p class="text-muted">Content will be available soon.</p>
            @endif
        </div>
        <div class="col-md-5">
            @if(isset($onDemandService) && $onDemandService && $onDemandService->image)
                <img src="{{ $onDemandService->image_url }}" alt="On Demand Service" class="img-fluid rounded shadow-sm">
            @endif
            @if(isset($onDemandService) && $onDemandService && $onDemandService->footer_short_description)
                <div class="card mt-3">
                    <div class="card-body">
                        <p class="mb-0">{!! $onDemandService->footer_short_description !!}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</main>
@endsection

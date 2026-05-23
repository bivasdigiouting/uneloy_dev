@extends('layouts.public')

@section('content')
<main class="container my-4" style="min-height: 50vh;">
    <div class="row">
        <div class="col-md-7">
            <h1 class="mb-3">U-Mart</h1>
            @if(isset($uonlyByAppsUMart) && $uonlyByAppsUMart)
                @if($uonlyByAppsUMart->text_header)
                    <h4 class="text-primary">{{ $uonlyByAppsUMart->text_header }}</h4>
                @endif
                <div class="mt-3">
                    {!! $uonlyByAppsUMart->text_description !!}
                </div>
            @else
                <p class="text-muted">U-Mart content will be available soon.</p>
            @endif
        </div>
        <div class="col-md-5">
            @if(isset($uonlyByAppsUMart) && $uonlyByAppsUMart && $uonlyByAppsUMart->image)
                <img src="{{ $uonlyByAppsUMart->image_url }}" alt="Uonly By Apps U-Mart" class="img-fluid rounded shadow-sm">
            @endif
            @if(isset($uonlyByAppsUMart) && $uonlyByAppsUMart && $uonlyByAppsUMart->footer_short_description)
                <div class="card mt-3">
                    <div class="card-body">
                        <p class="mb-0">{!! $uonlyByAppsUMart->footer_short_description !!}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</main>
@endsection


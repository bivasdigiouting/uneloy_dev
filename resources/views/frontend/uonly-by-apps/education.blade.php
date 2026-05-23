@extends('layouts.public')

@section('content')
<main class="container my-4" style="min-height: 50vh;">
    <div class="row">
        <div class="col-md-7">
            <h1 class="mb-3">Education</h1>
            @if(isset($uonlyByAppsEducation) && $uonlyByAppsEducation)
                @if($uonlyByAppsEducation->text_header)
                    <h4 class="text-primary">{{ $uonlyByAppsEducation->text_header }}</h4>
                @endif
                <div class="mt-3">
                    {!! $uonlyByAppsEducation->text_description !!}
                </div>
            @else
                <p class="text-muted">Education content will be available soon.</p>
            @endif
        </div>
        <div class="col-md-5">
            @if(isset($uonlyByAppsEducation) && $uonlyByAppsEducation && $uonlyByAppsEducation->image)
                <img src="{{ $uonlyByAppsEducation->image_url }}" alt="Uonly By Apps Education" class="img-fluid rounded shadow-sm">
            @endif
            @if(isset($uonlyByAppsEducation) && $uonlyByAppsEducation && $uonlyByAppsEducation->footer_short_description)
                <div class="card mt-3">
                    <div class="card-body">
                        <p class="mb-0">{!! $uonlyByAppsEducation->footer_short_description !!}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</main>
@endsection


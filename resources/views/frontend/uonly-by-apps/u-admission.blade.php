@extends('layouts.public')

@section('content')
<main class="container my-4" style="min-height: 50vh;">
    <div class="row">
        <div class="col-md-7">
            <h1 class="mb-3">U-Admission</h1>
            @if(isset($uonlyByAppsUAdmission) && $uonlyByAppsUAdmission)
                @if($uonlyByAppsUAdmission->text_header)
                    <h4 class="text-primary">{{ $uonlyByAppsUAdmission->text_header }}</h4>
                @endif
                <div class="mt-3">
                    {!! $uonlyByAppsUAdmission->text_description !!}
                </div>
            @else
                <p class="text-muted">U-Admission content will be available soon.</p>
            @endif
        </div>
        <div class="col-md-5">
            @if(isset($uonlyByAppsUAdmission) && $uonlyByAppsUAdmission && $uonlyByAppsUAdmission->image)
                <img src="{{ $uonlyByAppsUAdmission->image_url }}" alt="Uonly By Apps U-Admission" class="img-fluid rounded shadow-sm">
            @endif
            @if(isset($uonlyByAppsUAdmission) && $uonlyByAppsUAdmission && $uonlyByAppsUAdmission->footer_short_description)
                <div class="card mt-3">
                    <div class="card-body">
                        <p class="mb-0">{!! $uonlyByAppsUAdmission->footer_short_description !!}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</main>
@endsection


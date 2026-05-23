@extends('layouts.public')

@section('content')
<div class="container" style="padding: 50px 0;">
    <div class="row">
        <div class="col-12">
            <h1>{{ $page->title }}</h1>
            <div class="content-body mt-4">
                {!! $page->content !!}
            </div>
        </div>
    </div>
</div>
@endsection

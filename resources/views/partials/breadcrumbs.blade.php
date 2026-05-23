@php
    $menuService = app(\App\Services\MenuService::class);
    $breadcrumbs = $menuService->generateBreadcrumbs(request()->path());
@endphp

@if(count($breadcrumbs) > 1)
<div class="banner-bar">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="banner-content">
                    <h1>{{ end($breadcrumbs)['title'] }}</h1>
                    <ul class="location">
                        @foreach($breadcrumbs as $index => $breadcrumb)
                            @if($index === count($breadcrumbs) - 1)
                                <li>{{ $breadcrumb['title'] }}</li>
                            @else
                                <li><a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['title'] }}</a></li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
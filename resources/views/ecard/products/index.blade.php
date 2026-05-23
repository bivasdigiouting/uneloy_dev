@extends('ecard.ecard')
@section('title', 'Product List')
@section('content')
<div class="container-fluid py-3">
    <h4 class="mb-4">Product List</h4>
    @foreach($categories as $category)
        @if($category->products->count() > 0)
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">{{ $category->name }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($category->products as $product)
                    <div class="col-md-3 mb-3">
                        <div class="card h-100 border-0 shadow-sm">
                            <img src="{{ $product->image ? asset('storage/'.$product->image) : asset('assets/img/no-image.png') }}" class="card-img-top" alt="{{ $product->name }}" style="height: 150px; object-fit: cover;">
                            <div class="card-body p-2 text-center">
                                <h6 class="card-title text-truncate" title="{{ $product->name }}">{{ $product->name }}</h6>
                                <p class="card-text text-primary fw-bold">₹{{ number_format($product->price, 2) }}</p>
                                @if($product->description)
                                <small class="text-muted d-block text-truncate" title="{{ $product->description }}">{{ $product->description }}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    @endforeach
</div>
@endsection

@extends('admin.layouts.app')

@section('title', 'Vendor Products Approval')

@section('content')
<div class="row layout-top-spacing">
    <div class="col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4>Vendor Products Approval Queue</h4>
                    </div>
                </div>
            </div>
            <div class="widget-content widget-content-area border-top">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Vendor</th>
                                <th>Product Details</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td>
                                        @if($product->image)
                                            <img src="{{ \Illuminate\Support\Str::startsWith($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}" alt="product" width="50" height="50" style="object-fit: cover; border-radius: 4px;">
                                        @else
                                            <span class="badge badge-light-secondary">No Image</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($product->vendor)
                                            <span class="text-primary fw-bold">{{ $product->vendor->vendor_name ?? 'Unknown Vendor' }}</span><br>
                                            <small>{{ $product->vendor->mobile_no ?? '' }}</small>
                                        @else
                                            <span class="text-warning">Vendor Not Found</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $product->name }}</strong><br>
                                        <small class="text-muted">Category: {{ $product->category }}</small>
                                    </td>
                                    <td>₹{{ number_format($product->price, 2) }}</td>
                                    <td>
                                        @if($product->stock > 0)
                                            <span class="badge badge-success">{{ $product->stock }}</span>
                                        @else
                                            <span class="badge badge-danger">Out of Stock</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($product->admin_status === 'approved')
                                            <span class="badge badge-success">Approved</span>
                                        @elseif($product->admin_status === 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @else
                                            <span class="badge badge-danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($product->admin_status === 'pending')
                                            <div class="d-flex justify-content-center gap-2">
                                                <form action="{{ route('admin.vendor-products.approve', $product->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" title="Approve">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.vendor-products.reject', $product->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Reject">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        @elseif($product->admin_status === 'approved')
                                            <form action="{{ route('admin.vendor-products.reject', $product->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Suspend</button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.vendor-products.approve', $product->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success">Re-Approve</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        No vendor products available.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

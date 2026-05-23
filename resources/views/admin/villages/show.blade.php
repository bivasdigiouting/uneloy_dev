@extends('layouts.admin')

@section('title', 'Village/Town Details')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Village/Town Details</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.villages.index') }}">Village/Town Master</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Details</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Village/Town Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Name:</strong> {{ $village->village_name }}</p>
                            <p><strong>Status:</strong> {{ ucfirst($village->status) }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>State:</strong> {{ $village->state->state_name ?? 'N/A' }}</p>
                            <p><strong>District:</strong> {{ $village->district->district_name ?? 'N/A' }}</p>
                            <p><strong>City:</strong> {{ $village->city->city_name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('admin.villages.edit', $village->id) }}" class="btn btn-primary"><i class="ti ti-edit me-1"></i> Edit</a>
                        <a href="{{ route('admin.villages.index') }}" class="btn btn-light">Back to List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@extends('layouts.admin')

@section('title', 'Add Notification')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Add Notification</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a></li>
                    <li class="breadcrumb-item">Notification Modules</li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.notifications.index') }}">Notifications</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <a href="{{ route('admin.notifications.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center">
                    <i class="ti ti-arrow-left me-1"></i>Back to list
                </a>
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Notification Details</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.notifications.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Send To<span class="text-danger">*</span></label>
                        <select name="send_to" class="form-select" required>
                            <option value="">Select audience</option>
                            <option value="ecard" {{ old('send_to')==='ecard'?'selected':'' }}>E-Card</option>
                            <option value="ecard_seva" {{ old('send_to')==='ecard_seva'?'selected':'' }}>E-Card Seva</option>
                            <option value="vendor" {{ old('send_to')==='vendor'?'selected':'' }}>Vendor</option>
                        </select>
                    </div>
                    <div class="col-md-8 mb-3">
                        <label class="form-label">Title<span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="4" class="form-control">{{ old('description') }}</textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
 </div>
@endsection
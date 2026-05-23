@extends('layouts.admin')

@section('title', 'Edit Notification')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Edit Notification</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a></li>
                    <li class="breadcrumb-item">Notification Modules</li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.notifications.index') }}">Notifications</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
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
            <form action="{{ route('admin.notifications.update', $notification) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Send To<span class="text-danger">*</span></label>
                        <select name="send_to" class="form-select" required>
                            <option value="ecard" {{ $notification->send_to==='ecard'?'selected':'' }}>E-Card</option>
                            <option value="ecard_seva" {{ $notification->send_to==='ecard_seva'?'selected':'' }}>E-Card Seva</option>
                            <option value="vendor" {{ $notification->send_to==='vendor'?'selected':'' }}>Vendor</option>
                        </select>
                    </div>
                    <div class="col-md-8 mb-3">
                        <label class="form-label">Title<span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $notification->title) }}" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="4" class="form-control">{{ old('description', $notification->description) }}</textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        @if($notification->image_path)
                            <div class="mt-2">
                                <img src="{{ asset('storage/'.$notification->image_path) }}" alt="Image" style="max-height:100px;">
                            </div>
                        @endif
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy"></i> Save Changes</button>
                </div>
            </form>
        </div>
    </div>
 </div>
@endsection
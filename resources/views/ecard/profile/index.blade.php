@extends('ecard.ecard')

@section('title', 'Profile - E-Card')

@section('content')
<div class="content">
    <div class="content-inner">
        <div class="row g-4">
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Profile</h5>
                        <span class="text-muted small"><i class="fas fa-id-card me-1"></i>E-Card</span>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        <div class="text-center mb-3">
                            <div class="rounded-circle overflow-hidden d-inline-block" style="width: 128px; height: 128px; background: #f3f4f6;">
                                @if(!empty($avatarUrl))
                                    <img src="{{ $avatarUrl }}" alt="Avatar" class="img-fluid" style="object-fit: cover; width: 128px; height: 128px;">
                                @else
                                    <div class="d-flex align-items-center justify-content-center" style="width: 128px; height: 128px;">
                                        <i class="fas fa-user-circle" style="font-size: 72px; color: #9ca3af;"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="mt-2">
                                <div class="fw-semibold">{{ $user->full_name ?? trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) }}</div>
                                <div class="text-muted small">User ID: {{ $user->user_id ?? $user->id }}</div>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('ecard.profile.avatar.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Change Profile Image</label>
                                <input type="file" name="avatar" class="form-control" accept="image/png,image/jpeg,image/webp" required>
                                @error('avatar')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-upload me-1"></i>Upload</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Account Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" value="{{ $user->first_name }}" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" value="{{ $user->last_name }}" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Mobile No</label>
                                <input type="text" class="form-control" value="{{ $user->mobile_no }}" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="text" class="form-control" value="{{ $user->email_id }}" disabled>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Address</label>
                                <input type="text" class="form-control" value="{{ $user->current_address }}" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
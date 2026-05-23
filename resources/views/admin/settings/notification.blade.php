@extends('layouts.admin')

@section('title', 'Notification Settings')

@section('content')

    <div class="content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Notification Settings</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Settings</li>
                        <li class="breadcrumb-item active">Notification Settings</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /Page Header -->

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Firebase Configuration</h4>
                        <p class="card-text">Set Firebase keys used for sending notifications.</p>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.settings.notification.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="form-section">Firebase Keys</h5>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>FCM Server Key</label>
                                        <textarea name="firebase_server_key" rows="3" class="form-control @error('firebase_server_key') is-invalid @enderror" placeholder="Enter FCM server key">{{ old('firebase_server_key', $settings->firebase_server_key) }}</textarea>
                                        @error('firebase_server_key')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Use the Firebase Cloud Messaging server key (legacy). For HTTP v1, store service account credentials securely.</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Firebase API Key</label>
                                        <input type="text" name="firebase_api_key" class="form-control @error('firebase_api_key') is-invalid @enderror" value="{{ old('firebase_api_key', $settings->firebase_api_key) }}" placeholder="Enter Firebase API key">
                                        @error('firebase_api_key')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Project ID</label>
                                        <input type="text" name="firebase_project_id" class="form-control @error('firebase_project_id') is-invalid @enderror" value="{{ old('firebase_project_id', $settings->firebase_project_id) }}" placeholder="Enter Firebase project ID">
                                        @error('firebase_project_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Sender ID (Messaging)</label>
                                        <input type="text" name="firebase_sender_id" class="form-control @error('firebase_sender_id') is-invalid @enderror" value="{{ old('firebase_sender_id', $settings->firebase_sender_id) }}" placeholder="Enter Firebase sender ID">
                                        @error('firebase_sender_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>App ID</label>
                                        <input type="text" name="firebase_app_id" class="form-control @error('firebase_app_id') is-invalid @enderror" value="{{ old('firebase_app_id', $settings->firebase_app_id) }}" placeholder="Enter Firebase app ID">
                                        @error('firebase_app_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
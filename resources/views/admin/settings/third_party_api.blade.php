@extends('layouts.admin')

@section('content')
    <div class="page-header mb-4">
        <h1 class="page-title">Third Party Api Settings</h1>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Dashboard</a> / 
            <span>System Settings</span> / 
            <span>Third Party Api</span>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Configuration</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.settings.third-party-api.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="third_party_api_username">Api Username</label>
                            <input type="text" id="third_party_api_username" name="third_party_api_username"
                                   class="form-control @error('third_party_api_username') is-invalid @enderror"
                                   value="{{ old('third_party_api_username', $settings->third_party_api_username) }}" required>
                            @error('third_party_api_username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="third_party_api_token">Api Token</label>
                            <input type="text" id="third_party_api_token" name="third_party_api_token"
                                   class="form-control @error('third_party_api_token') is-invalid @enderror"
                                   value="{{ old('third_party_api_token', $settings->third_party_api_token) }}" required>
                            @error('third_party_api_token')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="third_party_api_url">Api URL</label>
                            <input type="url" id="third_party_api_url" name="third_party_api_url"
                                   class="form-control @error('third_party_api_url') is-invalid @enderror"
                                   value="{{ old('third_party_api_url', $settings->third_party_api_url) }}" required>
                            @error('third_party_api_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
@endsection
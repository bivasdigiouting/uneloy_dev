@extends('layouts.admin')

@section('content')
    <div class="page-header mb-4">
        <h1 class="page-title">Recharge API Settings</h1>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Dashboard</a> /
            <span>System Settings</span> /
            <span>Recharge API</span>
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
            <form action="{{ route('admin.settings.recharge-api.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="recharge_api_username">API Username</label>
                            <input type="text" id="recharge_api_username" name="recharge_api_username"
                                   class="form-control @error('recharge_api_username') is-invalid @enderror"
                                   value="{{ old('recharge_api_username', $settings->recharge_api_username) }}" required>
                            @error('recharge_api_username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="recharge_api_token">API Token</label>
                            <input type="text" id="recharge_api_token" name="recharge_api_token"
                                   class="form-control @error('recharge_api_token') is-invalid @enderror"
                                   value="{{ old('recharge_api_token', $settings->recharge_api_token) }}" required>
                            @error('recharge_api_token')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="recharge_callback_url">Callback URL</label>
                            <input type="url" id="recharge_callback_url" name="recharge_callback_url"
                                   class="form-control @error('recharge_callback_url') is-invalid @enderror"
                                   value="{{ old('recharge_callback_url', $settings->recharge_callback_url) }}" placeholder="https://example.com/callback" required>
                            @error('recharge_callback_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="recharge_pan_redirect_url">PAN Card Redirection URL</label>
                            <input type="url" id="recharge_pan_redirect_url" name="recharge_pan_redirect_url"
                                   class="form-control @error('recharge_pan_redirect_url') is-invalid @enderror"
                                   value="{{ old('recharge_pan_redirect_url', $settings->recharge_pan_redirect_url) }}" placeholder="https://example.com/pan-redirect" required>
                            @error('recharge_pan_redirect_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="recharge_api_url">API URL</label>
                            <input type="url" id="recharge_api_url" name="recharge_api_url"
                                   class="form-control @error('recharge_api_url') is-invalid @enderror"
                                   value="{{ old('recharge_api_url', $settings->recharge_api_url) }}" placeholder="https://api.example.com" required>
                            @error('recharge_api_url')
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
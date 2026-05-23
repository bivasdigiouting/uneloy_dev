@extends('layouts.admin')

@section('content')
    <div class="page-header mb-4">
        <h1 class="page-title">Maintenance Mode</h1>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Dashboard</a> /
            <span>System Settings</span> /
            <span>Maintenance Mode</span>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h3 class="card-title mb-0">Status</h3>
            @if ($settings->maintenance_mode)
                <span class="badge bg-danger">Enabled</span>
            @else
                <span class="badge bg-success">Disabled</span>
            @endif
        </div>
        <div class="card-body">
            <form action="{{ route('admin.settings.maintenance.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="maintenance_mode" name="maintenance_mode" {{ old('maintenance_mode', $settings->maintenance_mode) ? 'checked' : '' }}>
                    <label class="form-check-label" for="maintenance_mode">
                        Enable maintenance mode for public website
                    </label>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="maintenance_title">Maintenance Title</label>
                            <input type="text" id="maintenance_title" name="maintenance_title"
                                   class="form-control @error('maintenance_title') is-invalid @enderror"
                                   value="{{ old('maintenance_title', $settings->maintenance_title ?? 'Under Maintenance') }}"
                                   placeholder="Under Maintenance">
                            @error('maintenance_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12 mt-3">
                        <div class="form-group">
                            <label for="maintenance_message">Maintenance Message</label>
                            <textarea id="maintenance_message" name="maintenance_message" rows="4"
                                      class="form-control @error('maintenance_message') is-invalid @enderror"
                                      placeholder="We are currently performing scheduled maintenance. Please check back soon.">{{ old('maintenance_message', $settings->maintenance_message ?? 'We are currently performing scheduled maintenance. Please check back soon.') }}</textarea>
                            @error('maintenance_message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <div class="alert alert-info mb-0">
                        When enabled, all non-admin pages will show the maintenance screen.
                        Admin panel URLs under <strong>/admin</strong> stay accessible.
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
@endsection

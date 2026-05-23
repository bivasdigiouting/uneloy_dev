@extends('layouts.admin')

@section('content')
<div class="container-fluid py-3">
    <div class="row mb-3">
        <div class="col">
            <h4>Payment Gateways</h4>
            <p class="text-muted mb-0">Configure PhonePe and Cashfree test/live keys and logos.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.payment-gateways.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <!-- PhonePe Card -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div>
                            <strong>PhonePe</strong>
                            <span class="ms-2 badge bg-secondary">{{ data_get($gateways['phonepe'], 'active_mode', 'test') }}</span>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="phonepe_is_enabled" name="phonepe_is_enabled" value="1" {{ old('phonepe_is_enabled', data_get($gateways['phonepe'], 'is_enabled')) ? 'checked' : '' }}>
                            <label class="form-check-label" for="phonepe_is_enabled">Enabled</label>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Gateway Logo</label>
                            <div class="d-flex align-items-center gap-3">
                                @php $logo = data_get($gateways['phonepe'], 'logo'); @endphp
                                @if($logo)
                                    <img src="{{ asset('storage/' . $logo) }}" alt="PhonePe Logo" style="height:48px;">
                                @else
                                    <span class="text-muted">No logo uploaded</span>
                                @endif
                                <input type="file" name="phonepe_logo" accept="image/*" class="form-control" style="max-width:250px;">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Active Mode</label>
                            <select name="phonepe_active_mode" class="form-select" style="max-width:200px;">
                                <option value="test" {{ old('phonepe_active_mode', data_get($gateways['phonepe'], 'active_mode')) === 'test' ? 'selected' : '' }}>Test</option>
                                <option value="live" {{ old('phonepe_active_mode', data_get($gateways['phonepe'], 'active_mode')) === 'live' ? 'selected' : '' }}>Live</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="mb-2">Test Keys</h6>
                                <div class="mb-2">
                                    <label class="form-label">Client ID</label>
                                    <input type="text" name="phonepe_test_client_id" class="form-control" value="{{ old('phonepe_test_client_id', data_get($gateways['phonepe'], 'test_config.client_id')) }}">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Client Secret</label>
                                    <input type="password" name="phonepe_test_client_secret" class="form-control" value="{{ old('phonepe_test_client_secret', data_get($gateways['phonepe'], 'test_config.client_secret')) }}">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Environment</label>
                                    <input type="text" name="phonepe_test_environment" class="form-control" value="{{ old('phonepe_test_environment', data_get($gateways['phonepe'], 'test_config.environment', 'TEST')) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="mb-2">Live Keys</h6>
                                <div class="mb-2">
                                    <label class="form-label">Client ID</label>
                                    <input type="text" name="phonepe_live_client_id" class="form-control" value="{{ old('phonepe_live_client_id', data_get($gateways['phonepe'], 'live_config.client_id')) }}">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Client Secret</label>
                                    <input type="password" name="phonepe_live_client_secret" class="form-control" value="{{ old('phonepe_live_client_secret', data_get($gateways['phonepe'], 'live_config.client_secret')) }}">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Environment</label>
                                    <input type="text" name="phonepe_live_environment" class="form-control" value="{{ old('phonepe_live_environment', data_get($gateways['phonepe'], 'live_config.environment', 'LIVE')) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cashfree Card -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div>
                            <strong>Cashfree</strong>
                            <span class="ms-2 badge bg-secondary">{{ data_get($gateways['cashfree'], 'active_mode', 'test') }}</span>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="cashfree_is_enabled" name="cashfree_is_enabled" value="1" {{ data_get($gateways['cashfree'], 'is_enabled') ? 'checked' : '' }}>
                            <label class="form-check-label" for="cashfree_is_enabled">Enabled</label>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Gateway Logo</label>
                            <div class="d-flex align-items-center gap-3">
                                @php $logo = data_get($gateways['cashfree'], 'logo'); @endphp
                                @if($logo)
                                    <img src="{{ asset('storage/' . $logo) }}" alt="Cashfree Logo" style="height:48px;">
                                @else
                                    <span class="text-muted">No logo uploaded</span>
                                @endif
                                <input type="file" name="cashfree_logo" accept="image/*" class="form-control" style="max-width:250px;">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Active Mode</label>
                            <select name="cashfree_active_mode" class="form-select" style="max-width:200px;">
                                <option value="test" {{ data_get($gateways['cashfree'], 'active_mode') === 'test' ? 'selected' : '' }}>Test</option>
                                <option value="live" {{ data_get($gateways['cashfree'], 'active_mode') === 'live' ? 'selected' : '' }}>Live</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="mb-2">Test Keys</h6>
                                <div class="mb-2">
                                    <label class="form-label">App ID</label>
                                    <input type="text" name="cashfree_test_app_id" class="form-control" value="{{ data_get($gateways['cashfree'], 'test_config.app_id') }}">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Secret Key</label>
                                    <input type="text" name="cashfree_test_secret_key" class="form-control" value="{{ data_get($gateways['cashfree'], 'test_config.secret_key') }}">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Environment</label>
                                    <input type="text" name="cashfree_test_environment" class="form-control" value="{{ data_get($gateways['cashfree'], 'test_config.environment', 'TEST') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="mb-2">Live Keys</h6>
                                <div class="mb-2">
                                    <label class="form-label">App ID</label>
                                    <input type="text" name="cashfree_live_app_id" class="form-control" value="{{ data_get($gateways['cashfree'], 'live_config.app_id') }}">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Secret Key</label>
                                    <input type="text" name="cashfree_live_secret_key" class="form-control" value="{{ data_get($gateways['cashfree'], 'live_config.secret_key') }}">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Environment</label>
                                    <input type="text" name="cashfree_live_environment" class="form-control" value="{{ data_get($gateways['cashfree'], 'live_config.environment', 'LIVE') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <button type="submit" class="btn btn-primary">Save Settings</button>
            </div>
        </div>
    </form>
</div>
@endsection

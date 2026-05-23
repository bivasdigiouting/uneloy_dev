@extends('layouts.admin')

@section('title', 'E Card Seva Product Commission')

@section('content')

    <div class="content">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
            <div class="my-auto mb-2">
                <h2 class="mb-1">E Card Seva Product Commission</h2>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                        </li>
                        <li class="breadcrumb-item">
                            Inhouse Product Management
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">E Card Seva Product Comm.</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- /Page Header -->

        <!-- Commission Setup Form -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Setup Commission Levels</h4>
                    </div>
                    <div class="card-body">
                        <form id="commission-form">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="inhouse_product_id" class="form-label">Inhouse Product <span class="text-danger">*</span></label>
                                        <select class="form-select" id="inhouse_product_id" name="inhouse_product_id" required>
                                            <option value="">Select Inhouse Product</option>
                                            @foreach($inhouseProducts as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">&nbsp;</label>
                                        <div>
                                            <button type="button" class="btn btn-info" id="show-details-btn">
                                                <i class="ti ti-eye me-2"></i>Show Details
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Commission Details Form -->
        <div class="row" id="commission-details" style="display: none;">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Commission Percentage Setup</h4>
                    </div>
                    <div class="card-body">
                        <form id="commission-details-form" method="POST">
                            @csrf
                            <input type="hidden" id="selected_product_id" name="inhouse_product_id">
                            <input type="hidden" id="commission_id" name="id">
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="state_member_commission" class="form-label">State e-Card Seva Commission (%) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="state_member_commission" name="state_member_commission" step="0.01" min="0" max="100" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="district_member_commission" class="form-label">District e-Card Seva Commission (%) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="district_member_commission" name="district_member_commission" step="0.01" min="0" max="100" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="block_member_commission" class="form-label">Block - e-Card Seva Commission (%) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="block_member_commission" name="block_member_commission" step="0.01" min="0" max="100" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="panchayat_member_commission" class="form-label">G P M e-Card Seva Commission (%) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="panchayat_member_commission" name="panchayat_member_commission" step="0.01" min="0" max="100" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="village_member_commission" class="form-label">e-Card Seva Commission (%) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="village_member_commission" name="village_member_commission" step="0.01" min="0" max="100" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="customer_commission" class="form-label">Member Commission (%) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="customer_commission" name="customer_commission" step="0.01" min="0" max="100" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                                            <label class="form-check-label" for="is_active">
                                                Active Status
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="text-end">
                                <button type="button" class="btn btn-secondary me-2" id="cancel-btn">Cancel</button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-device-floppy me-2"></i>Submit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Existing Commissions List -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Existing Commission Settings</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="commissions-table">
                                <thead>
                                    <tr>
                                        <th>Inhouse Product Name</th>
                                        <th>State e-Card Seva (%)</th>
                                        <th>District e-Card Seva (%)</th>
                                        <th>Block - e-Card Seva (%)</th>
                                        <th>G P M e-Card Seva (%)</th>
                                        <th>e-Card Seva (%)</th>
                                        <th>Member (%)</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($commissions as $commission)
                                        <tr>
                                            <td>{{ $commission->product ? $commission->product->name : 'N/A' }}</td>
                                            <td>{{ $commission->state_member_commission }}%</td>
                                            <td>{{ $commission->district_member_commission }}%</td>
                                            <td>{{ $commission->block_member_commission }}%</td>
                                            <td>{{ $commission->panchayat_member_commission }}%</td>
                                            <td>{{ $commission->village_member_commission }}%</td>
                                            <td>{{ $commission->customer_commission }}%</td>
                                            <td>
                                                <span class="badge {{ $commission->is_active ? 'badge-success' : 'badge-danger' }}">
                                                    {{ $commission->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                        Actions
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item edit-commission" href="#" data-id="{{ $commission->id }}">
                                                                <i class="ti ti-edit me-2"></i>Edit
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item toggle-status" href="#" data-id="{{ $commission->id }}">
                                                                <i class="ti ti-toggle-{{ $commission->is_active ? 'left' : 'right' }} me-2"></i>
                                                                {{ $commission->is_active ? 'Deactivate' : 'Activate' }}
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item text-danger delete-commission" href="#" data-id="{{ $commission->id }}">
                                                                <i class="ti ti-trash me-2"></i>Delete
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center">No commission settings found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
$(document).ready(function() {
    // Show details button click
    $('#show-details-btn').click(function() {
        const productId = $('#inhouse_product_id').val();
        
        if (!productId) {
            toastr.error('Please select an inhouse product first');
            return;
        }
        
        // Set the selected product ID
        $('#selected_product_id').val(productId);
        
        // Check if commission already exists for this product
        $.ajax({
            url: '{{ route("admin.ecard-seva-product-commissions.show-details") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                inhouse_product_id: productId
            },
            success: function(response) {
                if (response.success) {
                    // Show the commission details form
                    $('#commission-details').show();

                    // If existing data, populate the form
                    if (response.data.exists && response.data.commission) {
                        $('#commission_id').val(response.data.commission.id);
                        $('#state_member_commission').val(response.data.commission.state_member_commission);
                        $('#district_member_commission').val(response.data.commission.district_member_commission);
                        $('#block_member_commission').val(response.data.commission.block_member_commission);
                        $('#panchayat_member_commission').val(response.data.commission.panchayat_member_commission);
                        $('#village_member_commission').val(response.data.commission.village_member_commission);
                        $('#customer_commission').val(response.data.commission.customer_commission);
                        $('#is_active').prop('checked', response.data.commission.is_active);
                    } else {
                        // Clear form for new entry (default 0)
                        $('#commission_id').val('');
                        $('#commission-details-form')[0].reset();
                        $('#inhouse_product_id').val(productId); // Restore product selection
                        $('#selected_product_id').val(productId);
                        $('#is_active').prop('checked', true);
                        
                        // Set all inputs to 0 explicitly
                        $('#state_member_commission').val(0);
                        $('#district_member_commission').val(0);
                        $('#block_member_commission').val(0);
                        $('#panchayat_member_commission').val(0);
                        $('#village_member_commission').val(0);
                        $('#customer_commission').val(0);
                    }
                }
            },
            error: function() {
                toastr.error('Error loading commission details');
            }
        });
    });
    
    // Cancel button click
    $('#cancel-btn').click(function() {
        $('#commission-details').hide();
        $('#commission-form')[0].reset();
    });
    
    // Commission details form submit
    $('#commission-details-form').submit(function(e) {
        e.preventDefault();

        const formData = $(this).serialize();
        const commissionId = $('#commission_id').val();
        const url = commissionId ? 
            '{{ route("admin.ecard-seva-product-commissions.update", ":id") }}'.replace(':id', commissionId) :
            '{{ route("admin.ecard-seva-product-commissions.store") }}';
        const method = commissionId ? 'PUT' : 'POST';
        
        $.ajax({
            url: url,
            method: method,
            data: formData,
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    location.reload();
                } else {
                    toastr.error(response.message || 'Error saving commission');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const resp = xhr.responseJSON || {};
                    if (resp.message && !resp.errors) {
                        Swal.fire({ icon: 'error', title: 'Invalid Data', text: resp.message });
                    } else if (resp.errors) {
                        const errors = resp.errors;
                        const firstKey = Object.keys(errors)[0];
                        Swal.fire({ icon: 'error', title: 'Validation Failed', text: errors[firstKey][0] });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Validation Failed', text: 'Please fix the highlighted errors.' });
                    }
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Error saving commission' });
                }
            }
        });
    });
    
    // Edit commission
    $('.edit-commission').click(function(e) {
        e.preventDefault();
        const commissionId = $(this).data('id');
        
        $.ajax({
            url: '{{ route("admin.ecard-seva-product-commissions.edit", ":id") }}'.replace(':id', commissionId),
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const data = response.data;
                    $('#inhouse_product_id').val(data.inhouse_product_id);
                    $('#selected_product_id').val(data.inhouse_product_id);
                    $('#commission_id').val(data.id);
                    $('#state_member_commission').val(data.state_member_commission);
                    $('#district_member_commission').val(data.district_member_commission);
                    $('#block_member_commission').val(data.block_member_commission);
                    $('#panchayat_member_commission').val(data.panchayat_member_commission);
                    $('#village_member_commission').val(data.village_member_commission);
                    $('#customer_commission').val(data.customer_commission);
                    $('#is_active').prop('checked', data.is_active);
                    
                    $('#commission-details').show();
                }
            },
            error: function() {
                toastr.error('Error loading commission data');
            }
        });
    });
    
    // Toggle status
    $('.toggle-status').click(function(e) {
        e.preventDefault();
        const commissionId = $(this).data('id');
        
        $.ajax({
            url: '{{ route("admin.ecard-seva-product-commissions.toggle-status", ":id") }}'.replace(':id', commissionId),
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    location.reload();
                }
            },
            error: function() {
                toastr.error('Error updating status');
            }
        });
    });
    
    // Delete commission
    $('.delete-commission').click(function(e) {
        e.preventDefault();
        const commissionId = $(this).data('id');
        
        if (confirm('Are you sure you want to delete this commission setting?')) {
            $.ajax({
                url: '{{ route("admin.ecard-seva-product-commissions.destroy", ":id") }}'.replace(':id', commissionId),
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        location.reload();
                    }
                },
                error: function() {
                    toastr.error('Error deleting commission');
                }
            });
        }
    });
});
</script>
@endpush

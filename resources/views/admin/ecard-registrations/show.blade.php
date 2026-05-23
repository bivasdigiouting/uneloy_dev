@extends('layouts.admin')

@section('title', 'E-Card Registration Details')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">E-Card Registration Details</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0);">E-Card Seva</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.ecard-registrations.index') }}">Registration</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Registration Details</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <a href="{{ route('admin.ecard-registrations.index') }}" class="btn btn-light d-inline-flex align-items-center">
                    <i class="ti ti-arrow-left me-1"></i>Back to E-Card Registrations
                </a>
            </div>
            <div class="me-2 mb-2">
                <a href="{{ route('admin.ecard-registrations.edit', $ecardRegistration->id) }}" class="btn btn-primary d-inline-flex align-items-center">
                    <i class="ti ti-edit me-1"></i>Edit Registration
                </a>
            </div>
            @if($ecardRegistration->status == 'pending')
            <div class="me-2 mb-2">
                <button type="button" class="btn btn-success d-inline-flex align-items-center" onclick="updateStatus({{ $ecardRegistration->id }}, 'approved')">
                    <i class="ti ti-check me-1"></i>Approve
                </button>
            </div>
            <div class="me-2 mb-2">
                <button type="button" class="btn btn-danger d-inline-flex align-items-center" onclick="updateStatus({{ $ecardRegistration->id }}, 'rejected')">
                    <i class="ti ti-x me-1"></i>Reject
                </button>
            </div>
            @endif
        </div>
    </div>

    <!-- Registration Status -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center">
                    <h4 class="mb-3">Registration Status: 
                        @if($ecardRegistration->status == 'pending')
                            <span class="badge bg-warning text-dark fs-6">Pending</span>
                        @elseif($ecardRegistration->status == 'active')
                            <span class="badge bg-success fs-6">Active</span>
                        @elseif($ecardRegistration->status == 'inactive')
                            <span class="badge bg-secondary fs-6">Inactive</span>
                        @elseif($ecardRegistration->status == 'rejected')
                            <span class="badge bg-danger fs-6">Rejected</span>
                        @endif
                    </h4>
                    <p class="text-muted mb-2">Registration Date: {{ $ecardRegistration->created_at->format('d M Y, h:i A') }}</p>
                    <p class="text-muted mb-0">Wallet Balance: <strong>₹{{ number_format($ecardRegistration->wallet_balance, 2) }}</strong></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Section 1: Business Details -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Section 1: Business Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Business Name:</label>
                                <p class="mb-0">{{ $ecardRegistration->business_name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Mobile No.:</label>
                                <p class="mb-0">{{ $ecardRegistration->business_mobile ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">WhatsApp No.:</label>
                                <p class="mb-0">{{ $ecardRegistration->business_whatsapp ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Gmail ID:</label>
                                <p class="mb-0">{{ $ecardRegistration->business_gmail ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Business Full Address:</label>
                                <p class="mb-0">{{ $ecardRegistration->business_address ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Business GST No.:</label>
                                <p class="mb-0">{{ $ecardRegistration->business_gst ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Business UPI ID:</label>
                                <p class="mb-0">{{ $ecardRegistration->business_upi ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Business Location Map Link:</label>
                                <p class="mb-0">
                                    @if($ecardRegistration->business_location_map)
                                        <a href="{{ $ecardRegistration->business_location_map }}" target="_blank" class="text-primary">View on Map</a>
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Personal Details -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Section 2: Personal Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">First Name:</label>
                                <p class="mb-0">{{ $ecardRegistration->first_name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Middle Name:</label>
                                <p class="mb-0">{{ $ecardRegistration->middle_name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Last Name:</label>
                                <p class="mb-0">{{ $ecardRegistration->last_name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Full Name:</label>
                                <p class="mb-0">{{ $ecardRegistration->full_name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Father's Name:</label>
                                <p class="mb-0">{{ $ecardRegistration->father_name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Mother's Name:</label>
                                <p class="mb-0">{{ $ecardRegistration->mother_name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Blood Group:</label>
                                <p class="mb-0">{{ $ecardRegistration->blood_group ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Date of Birth:</label>
                                <p class="mb-0">{{ $ecardRegistration->date_of_birth ? $ecardRegistration->date_of_birth->format('d M Y') : 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Gender:</label>
                                <p class="mb-0">{{ $ecardRegistration->gender ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Marital Status:</label>
                                <p class="mb-0">{{ $ecardRegistration->marital_status ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Contact Details -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Section 3: Contact Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Current Address:</label>
                                <p class="mb-0">{{ $ecardRegistration->current_address ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Permanent Address:</label>
                                <p class="mb-0">{{ $ecardRegistration->permanent_address ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nationality:</label>
                                <p class="mb-0">{{ $ecardRegistration->nationality ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">State:</label>
                                <p class="mb-0">{{ $ecardRegistration->state ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">District:</label>
                                <p class="mb-0">{{ $ecardRegistration->district ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">City:</label>
                                <p class="mb-0">{{ $ecardRegistration->city ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">PIN Code:</label>
                                <p class="mb-0">{{ $ecardRegistration->pin_code ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Mobile No.:</label>
                                <p class="mb-0">{{ $ecardRegistration->mobile_no ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Phone No.:</label>
                                <p class="mb-0">{{ $ecardRegistration->phone_no ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email ID:</label>
                                <p class="mb-0">{{ $ecardRegistration->email_id ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Gmail ID:</label>
                                <p class="mb-0">{{ $ecardRegistration->gmail_id ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Live Location Map Link:</label>
                                <p class="mb-0">
                                    @if($ecardRegistration->live_location_map)
                                        <a href="{{ $ecardRegistration->live_location_map }}" target="_blank" class="text-primary">View on Map</a>
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 4: Bank Details -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Section 4: Bank Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">IFSC Code:</label>
                                <p class="mb-0">{{ $ecardRegistration->ifsc_code ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Bank Name:</label>
                                <p class="mb-0">{{ $ecardRegistration->bank_name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Branch Name:</label>
                                <p class="mb-0">{{ $ecardRegistration->branch_name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Account No.:</label>
                                <p class="mb-0">{{ $ecardRegistration->account_no ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">PAN No.:</label>
                                <p class="mb-0">{{ $ecardRegistration->pan_no ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Aadhaar No.:</label>
                                <p class="mb-0">{{ $ecardRegistration->aadhaar_no ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 5: Qualification & Experience Details -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Section 5: Qualification & Experience Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Last Qualification:</label>
                                <p class="mb-0">{{ $ecardRegistration->last_qualification ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Work Type:</label>
                                <p class="mb-0">{{ $ecardRegistration->work_type ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Work Experience:</label>
                                <p class="mb-0">{{ $ecardRegistration->work_experience ?? 'N/A' }}</p>
                            </div>
                        </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 6: KYC Documents -->
                @php
                    $deptLevelValue = strtolower(trim((string) $ecardRegistration->department_level));
                    $isMemberLevel = in_array($deptLevelValue, ['customer', 'member'], true);
                @endphp
                @if(! $isMemberLevel)
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0 text-primary">6. KYC Documents</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            @php
                                $kycDocs = [
                                    'aadhaar_front' => 'Aadhaar Front',
                                    'aadhaar_back' => 'Aadhaar Back',
                                    'pan_card' => 'PAN Card',
                                    'cheque_book' => 'Cheque Book',
                                    'business_document' => 'Business Document',
                                    'gst_document' => 'GST Document',
                                    'business_photo' => 'Business Photo',
                                    'signature' => 'Signature',
                                    'user_photo' => 'User Photo'
                                ];
                            @endphp
                            @foreach($kycDocs as $field => $label)
                                <div class="col-md-4">
                                    <div class="mb-2 fw-bold">{{ $label }}:</div>
                                    @if($ecardRegistration->$field)
                                        @php
                                            $ext = pathinfo($ecardRegistration->$field, PATHINFO_EXTENSION);
                                        @endphp
                                        @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                            <a href="{{ asset('storage/' . $ecardRegistration->$field) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $ecardRegistration->$field) }}" alt="{{ $label }}" class="img-thumbnail" style="height: 120px; width: 100%; object-fit: cover;">
                                            </a>
                                        @elseif(strtolower($ext) == 'pdf')
                                            <a href="{{ asset('storage/' . $ecardRegistration->$field) }}" target="_blank" class="btn btn-outline-primary btn-sm mt-2">
                                                <i class="ti ti-file-pdf me-1"></i> View PDF
                                            </a>
                                        @else
                                            <a href="{{ asset('storage/' . $ecardRegistration->$field) }}" target="_blank" class="btn btn-outline-secondary btn-sm mt-2">
                                                <i class="ti ti-file me-1"></i> View File
                                            </a>
                                        @endif
                                    @else
                                        <span class="text-muted fst-italic">Not Uploaded</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Registration Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Registration Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Registration ID:</label>
                        <p class="mb-0">#{{ $ecardRegistration->id }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Status:</label>
                        <p class="mb-0">
                            @if($ecardRegistration->status == 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($ecardRegistration->status == 'active')
                                <span class="badge bg-success">Active</span>
                            @elseif($ecardRegistration->status == 'inactive')
                                <span class="badge bg-secondary">Inactive</span>
                            @elseif($ecardRegistration->status == 'rejected')
                                <span class="badge bg-danger">Rejected</span>
                            @endif
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">KYC Status:</label>
                        <p class="mb-0">
                            @if($ecardRegistration->kyc_status == 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($ecardRegistration->kyc_status == 'approved')
                                <span class="badge bg-success">Approved</span>
                            @elseif($ecardRegistration->kyc_status == 'rejected')
                                <span class="badge bg-danger">Rejected</span>
                            @else
                                <span class="badge bg-secondary">N/A</span>
                            @endif
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Wallet Balance:</label>
                        <p class="mb-0 text-success fw-bold">₹{{ number_format($ecardRegistration->wallet_balance, 2) }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Created At:</label>
                        <p class="mb-0">{{ $ecardRegistration->created_at->format('d M Y, h:i A') }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Last Updated:</label>
                        <p class="mb-0">{{ $ecardRegistration->updated_at->format('d M Y, h:i A') }}</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if(! $isMemberLevel && $ecardRegistration->kyc_status == 'pending')
                            <button type="button" class="btn btn-success" onclick="updateKycStatus({{ $ecardRegistration->id }}, 'approved')">
                                <i class="ti ti-check me-1"></i>Approve KYC
                            </button>
                            <button type="button" class="btn btn-danger" onclick="updateKycStatus({{ $ecardRegistration->id }}, 'rejected')">
                                <i class="ti ti-x me-1"></i>Reject KYC
                            </button>
                            <hr class="my-1">
                        @endif
                        <a href="{{ route('admin.ecard-registrations.edit', $ecardRegistration->id) }}" class="btn btn-primary">
                            <i class="ti ti-edit me-1"></i>Edit Registration
                        </a>
                        @if($ecardRegistration->status == 'pending')
                        <button type="button" class="btn btn-success" onclick="updateStatus({{ $ecardRegistration->id }}, 'approved')">
                            <i class="ti ti-check me-1"></i>Approve Registration
                        </button>
                        <button type="button" class="btn btn-danger" onclick="updateStatus({{ $ecardRegistration->id }}, 'rejected')">
                            <i class="ti ti-x me-1"></i>Reject Registration
                        </button>
                        @endif
                        <button type="button" class="btn btn-outline-danger" onclick="deleteRegistration({{ $ecardRegistration->id }})">
                            <i class="ti ti-trash me-1"></i>Delete Registration
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Update registration status
    function updateStatus(id, status) {
        if (confirm('Are you sure you want to ' + status + ' this registration?')) {
            $.ajax({
                url: '{{ route("admin.ecard-registrations.update-status", ":id") }}'.replace(':id', id),
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: status
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('An error occurred while updating the status.');
                }
            });
        }
    }

    // Delete registration
    function deleteRegistration(id) {
        if (confirm('Are you sure you want to delete this registration? This action cannot be undone.')) {
            $.ajax({
                url: '{{ route("admin.ecard-registrations.destroy", ":id") }}'.replace(':id', id),
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        setTimeout(function() {
                            window.location.href = '{{ route("admin.ecard-registrations.index") }}';
                        }, 1000);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('An error occurred while deleting the registration.');
                }
            });
        }
    }

    // Update KYC status
    function updateKycStatus(id, status) {
        if (confirm('Are you sure you want to mark KYC as ' + status + '?')) {
            $.ajax({
                url: '{{ route("admin.ecard-registrations.update-kyc-status", ":id") }}'.replace(':id', id),
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: status
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('An error occurred while updating the KYC status.');
                }
            });
        }
    }
</script>
@endpush

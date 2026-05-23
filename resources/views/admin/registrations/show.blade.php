@extends('layouts.admin')

@section('title', 'Registration Details')

@section('content')

    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Registration Details</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.registrations.index') }}">Registrations</a></li>
                        <li class="breadcrumb-item active">Registration Details</li>
                    </ul>
                </div>
                <div class="col-auto float-right ml-auto">
                    <div class="btn-group" role="group">
                        <a href="{{ route('admin.registrations.edit', $registration->id) }}" class="btn btn-primary">
                            <i class="fa fa-edit"></i> Edit
                        </a>
                        @if($registration->status == 'pending')
                        <button type="button" class="btn btn-success" onclick="updateStatus({{ $registration->id }}, 'approved')">
                            <i class="fa fa-check"></i> Approve
                        </button>
                        <button type="button" class="btn btn-danger" onclick="updateStatus({{ $registration->id }}, 'rejected')">
                            <i class="fa fa-times"></i> Reject
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Header -->

        <!-- Registration Status -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body text-center">
                        <h4>Registration Status: 
                            @if($registration->status == 'pending')
                                <span class="badge badge-warning badge-lg">Pending</span>
                            @elseif($registration->status == 'approved')
                                <span class="badge badge-success badge-lg">Approved</span>
                            @elseif($registration->status == 'rejected')
                                <span class="badge badge-danger badge-lg">Rejected</span>
                            @endif
                        </h4>
                        <p class="text-muted">Registration Date: {{ $registration->created_at->format('d M Y, h:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 1: Official Details -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">1. Official Details</h4>
                    </div>
                    <div class="card-body">
                        @php
                            $departmentLabels = [
                                'state_level' => 'State e-Card Seva',
                                'district_level' => 'District e-Card Seva',
                                'block_level' => 'Block - e-Card Seva',
                                'panchayat_level' => 'G P M e-Card Seva',
                                'village_level' => 'e-Card Seva',
                                'customer' => 'Member',
                            ];
                        @endphp
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>User ID:</strong></label>
                                    <p>{{ $registration->user_id ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>Department Level:</strong></label>
                                    <p>{{ $departmentLabels[$registration->department_level] ?? ucfirst(str_replace('_',' ', $registration->department_level)) }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>Aadhaar Number:</strong></label>
                                    <p>{{ $registration->aadhaar_no ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>OTP Required:</strong></label>
                                    <p>{{ ($registration->otp_required ?? false) ? 'Yes' : 'No' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>OTP Verified:</strong></label>
                                    <p>{{ ($registration->otp_verified ?? false) ? 'Yes' : 'No' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Personal Details -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">2. Personal Details</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><strong>First Name:</strong></label>
                                    <p>{{ $registration->first_name ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><strong>Middle Name:</strong></label>
                                    <p>{{ $registration->middle_name ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><strong>Last Name:</strong></label>
                                    <p>{{ $registration->last_name ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>Father's Name:</strong></label>
                                    <p>{{ $registration->father_name ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>Mother's Name:</strong></label>
                                    <p>{{ $registration->mother_name ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><strong>Blood Group:</strong></label>
                                    <p>{{ $registration->blood_group ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><strong>Date of Birth:</strong></label>
                                    <p>{{ $registration->date_of_birth ? $registration->date_of_birth->format('d M Y') : 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><strong>Gender:</strong></label>
                                    <p>{{ $registration->gender ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>Marital Status:</strong></label>
                                    <p>{{ $registration->marital_status ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 3: Contact Details -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">3. Contact Details</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label><strong>Current Address:</strong></label>
                                    <p>{{ $registration->current_address ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label><strong>Permanent Address:</strong></label>
                                    <p>{{ $registration->permanent_address ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>Nationality (Country):</strong></label>
                                    <p>{{ $registration->nationality ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>State:</strong></label>
                                    <p>{{ $registration->state ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>District:</strong></label>
                                    <p>{{ $registration->district ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>City:</strong></label>
                                    <p>{{ $registration->city ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>Pin Code:</strong></label>
                                    <p>{{ $registration->pin_code ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>Mobile No.:</strong></label>
                                    <p>{{ $registration->mobile_no ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>Phone No.:</strong></label>
                                    <p>{{ $registration->phone_no ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>E-Mail ID:</strong></label>
                                    <p>{{ $registration->email_id ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>Gmail ID:</strong></label>
                                    <p>{{ $registration->gmail_id ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label><strong>Live Location (Map):</strong></label>
                                    <p>{{ $registration->live_location_map ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 4: Bank Details -->
        

        <!-- Section 5: Qualification & Experience Details -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">5. Qualification & Experience Details</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><strong>Last Qualification:</strong></label>
                                    <p>{{ $registration->last_qualification ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><strong>Work Type:</strong></label>
                                    <p>{{ $registration->work_type ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><strong>Work Experience:</strong></label>
                                    <p>{{ $registration->work_experience ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    function updateStatus(id, status) {
        if (confirm('Are you sure you want to ' + status + ' this registration?')) {
            $.ajax({
                url: '{{ url("admin/registrations") }}/' + id + '/status',
                type: 'POST',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'status': status
                },
                success: function(response) {
                    location.reload();
                },
                error: function(xhr) {
                    alert('Error updating status');
                }
            });
        }
    }
</script>
@endsection

@extends('layouts.admin')

@section('title', 'User Details')

@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">User Details</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
                        <li class="breadcrumb-item active">{{ $user->name }}</li>
                    </ul>
                </div>
                <div class="col-auto float-end ms-auto">
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn add-btn"><i class="fa fa-edit"></i> Edit User</a>
                </div>
            </div>
        </div>
        <!-- /Page Header -->

        <div class="card mb-0">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="profile-view">
                            <div class="profile-img-wrap">
                                <div class="profile-img">
                                    @if($user->image)
                                        <img alt="" src="{{ Storage::url($user->image) }}">
                                    @else
                                        <img alt="" src="{{ asset('backend-assets/img/profiles/avatar-02.jpg') }}">
                                    @endif
                                </div>
                            </div>
                            <div class="profile-basic">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="profile-info-left">
                                            <h3 class="user-name m-t-0 mb-0">{{ $user->name }}</h3>
                                            <h6 class="text-muted">{{ $user->role ? $user->role->name : 'No Role Assigned' }}</h6>
                                            <small class="text-muted">{{ $user->email }}</small>
                                            <div class="staff-id">User ID : {{ $user->id }}</div>
                                            <div class="small doj text-muted">Date of Joining : {{ $user->created_at->format('d M Y') }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <ul class="personal-info">
                                            <li>
                                                <div class="title">Phone:</div>
                                                <div class="text">{{ $user->phone ?? 'Not provided' }}</div>
                                            </li>
                                            <li>
                                                <div class="title">Email:</div>
                                                <div class="text">{{ $user->email }}</div>
                                            </li>
                                            <li>
                                                <div class="title">Birthday:</div>
                                                <div class="text">Not provided</div>
                                            </li>
                                            <li>
                                                <div class="title">Address:</div>
                                                <div class="text">{{ $user->address ?? 'Not provided' }}</div>
                                            </li>
                                            <li>
                                                <div class="title">Gender:</div>
                                                <div class="text">Not provided</div>
                                            </li>
                                            <li>
                                                <div class="title">Reports to:</div>
                                                <div class="text">
                                                    <div class="avatar-box">
                                                        <div class="avatar avatar-xs">
                                                            <img src="{{ asset('backend-assets/img/profiles/avatar-16.jpg') }}" alt="">
                                                        </div>
                                                    </div>
                                                    <a href="#">Admin</a>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="pro-edit"><a data-target="#profile_info" data-toggle="modal" class="edit-icon" href="#"><i class="fa fa-pencil"></i></a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card tab-box">
            <div class="row user-tabs">
                <div class="col-lg-12 col-md-12 col-sm-12 line-tabs">
                    <ul class="nav nav-tabs nav-tabs-bottom">
                        <li class="nav-item"><a href="#emp_profile" data-toggle="tab" class="nav-link active">Profile</a></li>
                        <li class="nav-item"><a href="#emp_projects" data-toggle="tab" class="nav-link">Projects</a></li>
                        <li class="nav-item"><a href="#bank_statutory" data-toggle="tab" class="nav-link">Bank & Statutory <small class="text-danger">(Admin Only)</small></a></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="tab-content">
            <!-- Profile Info Tab -->
            <div id="emp_profile" class="pro-overview tab-pane fade show active">
                <div class="row">
                    <div class="col-md-6 d-flex">
                        <div class="card profile-box flex-fill">
                            <div class="card-body">
                                <h3 class="card-title">Personal Information</h3>
                                <ul class="personal-info">
                                    <li>
                                        <div class="title">Name</div>
                                        <div class="text">{{ $user->name }}</div>
                                    </li>
                                    <li>
                                        <div class="title">Email</div>
                                        <div class="text">{{ $user->email }}</div>
                                    </li>
                                    <li>
                                        <div class="title">Phone</div>
                                        <div class="text">{{ $user->phone ?? 'Not provided' }}</div>
                                    </li>
                                    <li>
                                        <div class="title">Role</div>
                                        <div class="text">{{ $user->role ? $user->role->name : 'No Role Assigned' }}</div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex">
                        <div class="card profile-box flex-fill">
                            <div class="card-body">
                                <h3 class="card-title">Address Information</h3>
                                <ul class="personal-info">
                                    <li>
                                        <div class="title">Address</div>
                                        <div class="text">{{ $user->address ?? 'Not provided' }}</div>
                                    </li>
                                    <li>
                                        <div class="title">City</div>
                                        <div class="text">{{ $user->city ?? 'Not provided' }}</div>
                                    </li>
                                    <li>
                                        <div class="title">State</div>
                                        <div class="text">{{ $user->state ?? 'Not provided' }}</div>
                                    </li>
                                    <li>
                                        <div class="title">Country</div>
                                        <div class="text">{{ $user->country ?? 'Not provided' }}</div>
                                    </li>
                                    <li>
                                        <div class="title">Postal Code</div>
                                        <div class="text">{{ $user->postal_code ?? 'Not provided' }}</div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Profile Info Tab -->
            
            <!-- Projects Tab -->
            <div class="tab-pane fade" id="emp_projects">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">Projects</h3>
                                <p class="text-muted">No projects assigned yet.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Projects Tab -->
            
            <!-- Bank Statutory Tab -->
            <div class="tab-pane fade" id="bank_statutory">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Bank Information</h3>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="col-form-label">Bank name</label>
                                    <input class="form-control" type="text" value="Not provided" readonly>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="col-form-label">Bank account No.</label>
                                    <input class="form-control" type="text" value="Not provided" readonly>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="col-form-label">IFSC Code</label>
                                    <input class="form-control" type="text" value="Not provided" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Bank Statutory Tab -->
        </div>
    </div>
</div>
@endsection
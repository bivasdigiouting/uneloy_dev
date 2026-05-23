@extends('layouts.admin')
@section('title','Notification Master')
@section('content')
<div class="content">
  <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
    <div class="my-auto mb-2">
      <h2 class="mb-1">Notification Master</h2>
      <nav>
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a></li>
          <li class="breadcrumb-item">User Management</li>
          <li class="breadcrumb-item active">Notification Master</li>
        </ol>
      </nav>
    </div>
  </div>
  <div class="card"><div class="card-body">Page loaded. Please use sidebar links.</div></div>
</div>
@endsection
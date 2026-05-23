@extends('layouts.admin')
@section('page_title', 'Login History Report')

@section('content')
<div class="container-fluid">
  <div class="row mb-2">
    <div class="col-sm-6">
      <h4 class="page-title">Login History</h4>
    </div>
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">Report Modules</li>
        <li class="breadcrumb-item active">Login History</li>
      </ol>
    </div>
  </div>

  <div class="row g-3 mb-3">
    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body py-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <p class="mb-1 text-muted">Sessions</p>
              <h5 class="mb-0" id="summary-count">0</h5>
            </div>
            <i class="ti ti-list-details fs-24 text-info"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card mb-3 shadow-sm">
    <div class="card-header">
      <h6 class="mb-0">Filters</h6>
    </div>
    <div class="card-body">
      <div class="row g-2 align-items-end">
        <div class="col-md-3">
          <label for="entity_type" class="form-label">User Type</label>
          <select id="entity_type" class="form-select form-select-sm">
            <option value="">All</option>
            <option value="registration">Department-level User</option>
            <option value="user">User</option>
          </select>
        </div>
        <div class="col-md-3">
          <label for="start_date" class="form-label">Start Date</label>
          <input type="date" id="start_date" class="form-control form-control-sm">
        </div>
        <div class="col-md-3">
          <label for="end_date" class="form-label">End Date</label>
          <input type="date" id="end_date" class="form-control form-control-sm">
        </div>
        <div class="col-md-3">
          <label for="platform" class="form-label">Platform</label>
          <select id="platform" class="form-select form-select-sm">
            <option value="">All</option>
            <option value="web">Web</option>
            <option value="mobile">Mobile</option>
          </select>
        </div>
        <div class="col-md-3">
          <label for="user_id" class="form-label">UserId</label>
          <input type="text" id="user_id" class="form-control form-control-sm" placeholder="e.g., UP12345678">
        </div>
        <div class="col-md-3">
          <label for="mobile" class="form-label">Mobile</label>
          <input type="text" id="mobile" class="form-control form-control-sm" placeholder="e.g., +91...">
        </div>
        <div class="col-md-3">
          <label for="ip" class="form-label">IP Address</label>
          <input type="text" id="ip" class="form-control form-control-sm" placeholder="e.g., 203.0.113.5">
        </div>
        <div class="col-md-3">
          <label for="search" class="form-label">Search</label>
          <input type="text" id="search" class="form-control form-control-sm" placeholder="Name / Email / Agent">
        </div>
        <div class="col-md-6 d-flex gap-2">
          <button id="btnFilter" class="btn btn-primary btn-sm"><i class="ti ti-filter"></i> Apply</button>
          <button id="btnReset" class="btn btn-secondary btn-sm"><i class="ti ti-refresh"></i> Reset</button>
        </div>
      </div>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h6 class="mb-0">Login History Details</h6>
    </div>
    <div class="card-body">
      <table id="loginHistoryTable" class="table table-striped table-bordered" style="width:100%">
        <thead>
          <tr>
            <th>Login Time</th>
            <th>Logout Time</th>
            <th>Duration</th>
            <th>Platform</th>
            <th>IP Address</th>
            <th>UserId</th>
            <th>Name</th>
            <th>Mobile</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const table = $('#loginHistoryTable').DataTable({
    processing: true,
    serverSide: true,
    searching: false,
    lengthChange: true,
    ajax: {
      url: "{{ route('admin.reports.login-history.data') }}",
      data: function(d) {
        d.entity_type = $('#entity_type').val();
        d.start_date = $('#start_date').val();
        d.end_date = $('#end_date').val();
        d.platform = $('#platform').val();
        d.user_id = $('#user_id').val();
        d.mobile = $('#mobile').val();
        d.ip = $('#ip').val();
        d.search = $('#search').val();
      },
      dataSrc: function(json) {
        $('#summary-count').text(json.summary.count || 0);
        return json.data;
      }
    },
    columns: [
      { data: 'login_time', name: 'login_time' },
      { data: 'logout_time', name: 'logout_time' },
      { data: 'duration', name: 'duration' },
      { data: 'platform', name: 'platform' },
      { data: 'ip_address', name: 'ip_address' },
      { data: 'user_id', name: 'user_id' },
      { data: 'name', name: 'name' },
      { data: 'mobile', name: 'mobile' },
    ]
  });

  $('#btnFilter').on('click', function(){ table.ajax.reload(); });
  $('#btnReset').on('click', function(){
    $('#entity_type').val('');
    $('#start_date').val('');
    $('#end_date').val('');
    $('#platform').val('');
    $('#user_id').val('');
    $('#mobile').val('');
    $('#ip').val('');
    $('#search').val('');
    table.ajax.reload();
  });
});
</script>
@endpush
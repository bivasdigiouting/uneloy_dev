@extends('layouts.admin')
@section('title','Approve Kyc')
@section('content')
<div class="content">
  <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
    <div class="my-auto mb-2">
      <h2 class="mb-1">Approve Kyc</h2>
      <nav>
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a></li>
          <li class="breadcrumb-item">Vendor Management</li>
          <li class="breadcrumb-item active">Approve Kyc</li>
        </ol>
      </nav>
    </div>
  </div>

  <div class="card mb-3">
    <div class="card-header"><strong>Filters</strong></div>
    <div class="card-body">
      <div class="row g-3 align-items-end">
        <div class="col-md-3">
          <label for="kyc_status" class="form-label">KYC Status</label>
          <select class="form-select" id="kyc_status" name="kyc_status">
            <option>All</option>
            <option>Incomplete</option>
            <option>GST Pending</option>
            <option>Complete</option>
          </select>
        </div>
        <div class="col-md-6">
          <label for="search_text" class="form-label">Search (name/email/mobile/pan/aadhar/gst)</label>
          <div class="input-group">
            <input type="text" class="form-control" id="search_text" name="search_text" placeholder="name / email / mobile / pan / aadhar / gst">
            <button id="searchBtn" class="btn btn-primary" type="button"><i class="ti ti-search"></i> Search</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header"><strong>Vendor KYC List</strong></div>
    <div class="card-body">
      <div class="table-responsive">
        <table id="vendorKycTable" class="table table-striped table-bordered w-100">
          <thead>
            <tr>
              <th>Sr. No.</th>
              <th>Vendor No.</th>
              <th>Name</th>
              <th>Email</th>
              <th>Mobile</th>
              <th>PAN</th>
              <th>Aadhar</th>
              <th>GST</th>
              <th>KYC Status</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
$(function() {
  var table = $('#vendorKycTable').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
      url: '{{ route('admin.vendor.approve-kyc.data') }}',
      type: 'GET',
      data: function(d) {
        let kyc = $('#kyc_status').val();
        d.kyc_status = (kyc === 'All') ? '' : kyc;
        d.search_text = $('#search_text').val();
      }
    },
    order: [[2, 'asc']],
    columns: [
      { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
      { data: 'vendor_number', name: 'vendor_number', defaultContent: '' },
      { data: 'name', name: 'name' },
      { data: 'email', name: 'email' },
      { data: 'mobile_no', name: 'mobile_no' },
      { data: 'pan_no', name: 'pan_no' },
      { data: 'aadhar_no', name: 'aadhar_no' },
      { data: 'gst_no', name: 'gst_no' },
      { data: 'kyc_status', name: 'kyc_status' },
    ],
    createdRow: function(row, data, dataIndex) {
      // Add serial number
      $('td:eq(0)', row).text(dataIndex + 1);
    }
  });

  $('#searchBtn').on('click', function() { table.ajax.reload(); });
  $('#kyc_status').on('change', function() { table.ajax.reload(); });
});
</script>
@endpush
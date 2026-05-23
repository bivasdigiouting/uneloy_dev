@extends('layouts.admin')
@section('page_title', 'Help & Support Report')

@section('content')
<div class="container-fluid">
  <div class="row mb-2">
    <div class="col-sm-6">
      <h4 class="page-title">Help & Support Report</h4>
    </div>
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">Report Modules</li>
        <li class="breadcrumb-item active">Help & Support Report</li>
      </ol>
    </div>
  </div>

  <div class="row g-3 mb-3">
    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body py-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <p class="mb-1 text-muted">Helpline Records</p>
              <h5 class="mb-0" id="summary-count">0</h5>
            </div>
            <i class="ti ti-lifebuoy fs-24 text-primary"></i>
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
          <label for="state_id" class="form-label">State</label>
          <select id="state_id" class="form-select form-select-sm">
            <option value="">All</option>
            @foreach($states as $state)
              <option value="{{ $state->id }}">{{ $state->state_name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3">
          <label for="district_id" class="form-label">District</label>
          <select id="district_id" class="form-select form-select-sm">
            <option value="">All</option>
          </select>
        </div>
        <div class="col-md-3">
          <label for="city_id" class="form-label">City</label>
          <select id="city_id" class="form-select form-select-sm">
            <option value="">All</option>
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
          <label for="search" class="form-label">Search</label>
          <input type="text" id="search" class="form-control form-control-sm" placeholder="Name / Number / Location">
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
      <h6 class="mb-0">Help & Support Details</h6>
    </div>
    <div class="card-body">
      <table id="helpSupportTable" class="table table-striped table-bordered" style="width:100%">
        <thead>
          <tr>
            <th>Created At</th>
            <th>Helpline Name</th>
            <th>Number</th>
            <th>State</th>
            <th>District</th>
            <th>City</th>
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
  const table = $('#helpSupportTable').DataTable({
    processing: true,
    serverSide: true,
    searching: false,
    lengthChange: true,
    ajax: {
      url: "{{ route('admin.reports.help-support.data') }}",
      data: function(d) {
        d.state_id = $('#state_id').val();
        d.district_id = $('#district_id').val();
        d.city_id = $('#city_id').val();
        d.start_date = $('#start_date').val();
        d.end_date = $('#end_date').val();
        d.search = $('#search').val();
      },
      dataSrc: function(json) {
        $('#summary-count').text(json.summary.count || 0);
        return json.data;
      }
    },
    columns: [
      { data: 'created_at', name: 'created_at' },
      { data: 'helpline_name', name: 'helpline_name' },
      { data: 'helpline_number', name: 'helpline_number' },
      { data: 'state_name', name: 'state_name' },
      { data: 'district_name', name: 'district_name' },
      { data: 'city_name', name: 'city_name' },
    ]
  });

  // Dependent selects for District and City
  $('#state_id').on('change', async function() {
    const stateId = this.value;
    $('#district_id').html('<option value="">All</option>');
    $('#city_id').html('<option value="">All</option>');
    if (!stateId) return;
    try {
      const url = "{{ route('admin.districts.by-state', ['state_id' => 'STATE_ID']) }}".replace('STATE_ID', stateId);
      const res = await fetch(url);
      const districts = await res.json();
      const options = ['<option value="">All</option>'];
      districts.forEach(d => options.push(`<option value="${d.id}">${d.district_name}</option>`));
      $('#district_id').html(options.join(''));
    } catch (e) { console.error('Failed to load districts', e); }
  });

  $('#district_id').on('change', async function() {
    const districtId = this.value;
    $('#city_id').html('<option value="">All</option>');
    if (!districtId) return;
    try {
      const url = "{{ route('admin.cities.by-district', ['district_id' => 'DISTRICT_ID']) }}".replace('DISTRICT_ID', districtId);
      const res = await fetch(url);
      const cities = await res.json();
      const options = ['<option value="">All</option>'];
      cities.forEach(c => options.push(`<option value="${c.id}">${c.city_name}</option>`));
      $('#city_id').html(options.join(''));
    } catch (e) { console.error('Failed to load cities', e); }
  });

  $('#btnFilter').on('click', function(){ table.ajax.reload(); });
  $('#btnReset').on('click', function(){
    $('#state_id').val('');
    $('#district_id').html('<option value="">All</option>');
    $('#city_id').html('<option value="">All</option>');
    $('#start_date').val('');
    $('#end_date').val('');
    $('#search').val('');
    table.ajax.reload();
  });
});
</script>
@endpush
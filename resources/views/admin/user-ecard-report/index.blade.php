@extends('layouts.admin')
@section('title','User E-Card Report')
@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
@endpush
@section('content')
<div class="content">
  <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
    <div class="my-auto mb-2">
      <h2 class="mb-1">User E-Card Report</h2>
      <nav><ol class="breadcrumb mb-0"><li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a></li><li class="breadcrumb-item">User Management</li><li class="breadcrumb-item active">User E-Card Report</li></ol></nav>
    </div>
    <div class="head-icons ms-2"><a href="javascript:void(0);" class="btn btn-primary" onclick="window.location.reload();"><i class="ti ti-refresh-dot"></i></a></div>
  </div>
  <div class="row"><div class="col-12"><div class="card"><div class="card-header"><h4 class="card-title">Filter</h4></div><div class="card-body">
    <form id="filterForm" class="row g-3">
      <div class="col-md-3"><label class="form-label">From Date</label><input type="date" class="form-control" id="from_date" name="from_date"></div>
      <div class="col-md-3"><label class="form-label">To Date</label><input type="date" class="form-control" id="to_date" name="to_date"></div>
      <div class="col-md-3"><label class="form-label">Status</label><select class="form-select" id="status" name="status"><option value="All">All</option><option value="Active">Active</option><option value="De-Active">De-Active</option></select></div>
      <div class="col-md-3"><label class="form-label">State</label><select class="form-select" id="state_id" name="state_id"><option value="">Select State</option>@foreach($states as $state)<option value="{{ $state->id }}">{{ $state->state_name }}</option>@endforeach</select></div>
      <div class="col-md-3"><label class="form-label">District</label><select class="form-select" id="district_id" name="district_id" disabled><option value="">Select District</option></select></div>
      <div class="col-md-3"><label class="form-label">City</label><select class="form-select" id="city_id" name="city_id" disabled><option value="">Select City</option></select></div>
      <div class="col-md-4"><label class="form-label">Search (Id/Name/Email)</label><input type="text" class="form-control" id="search_text" name="q" placeholder="Id / Name / Email"></div>
      <div class="col-md-5 d-flex align-items-end"><button type="button" id="btnSearch" class="btn btn-primary me-2">Search</button><button type="reset" id="btnReset" class="btn btn-secondary">Reset</button></div>
    </form>
  </div></div></div></div>
  <div class="row mt-3"><div class="col-12"><div class="card"><div class="card-header d-flex justify-content-between align-items-center"><h4 class="card-title mb-0">E-Card Report List</h4></div><div class="card-body"><div class="table-responsive">
    <table id="ecardReportTable" class="table table-striped table-bordered nowrap" style="width:100%"><thead><tr>
      <th>Sr. No.</th><th>Select <input type="checkbox" id="selectAll"></th><th>Level ID No.</th><th>ID No.</th><th>State Name</th><th>District Name</th><th>City Name</th><th>e-card Number</th><th>e-card No.</th><th>Status</th><th>EEV No.</th><th>Security Number</th><th>Create Date</th><th>Expiry Date</th><th>Status</th><th>Print Date</th><th>Action</th>
    </tr></thead><tbody></tbody></table>
  </div></div></div></div></div>
@endsection
@push('scripts')
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script>(function(){
  const table=$('#ecardReportTable').DataTable({processing:true,serverSide:true,responsive:true,pageLength:10,ajax:{url:'{{ route('admin.user-ecard-report.data') }}',data:function(d){d.from_date=$('#from_date').val();d.to_date=$('#to_date').val();d.status=$('#status').val();d.state_id=$('#state_id').val();d.district_id=$('#district_id').val();d.city_id=$('#city_id').val();d.q=$('#search_text').val();}},
  columns:[
    {data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false},
    {data:'select',name:'select',orderable:false,searchable:false},
    {data:'level_id_no',name:'level_id_no'},
    {data:'id_no',name:'id_no'},
    {data:'state_name',name:'state'},
    {data:'district_name',name:'district'},
    {data:'city_name',name:'city'},
    {data:'ecard_number',name:'ecard_number'},
    {data:'ecard_no',name:'ecard_no'},
    {data:'status_label',name:'status',orderable:false,searchable:false},
    {data:'eev_no',name:'eev_no'},
    {data:'security_number',name:'security_number'},
    {data:'create_date',name:'created_at'},
    {data:'expiry_date',name:'expiry_date'},
    {data:'status2',name:'status2',orderable:false},
    {data:'print_date',name:'print_date'},
    {data:'action',name:'action',orderable:false,searchable:false}
  ]});
  $('#btnSearch').on('click',function(){table.ajax.reload();});
  $('#btnReset').on('click',function(){$('#filterForm')[0].reset();$('#district_id').prop('disabled',true).empty().append('<option value="">Select District</option>');$('#city_id').prop('disabled',true).empty().append('<option value="">Select City</option>');table.ajax.reload();});
  $('#state_id').on('change',function(){const sid=$(this).val();$('#district_id').prop('disabled',true).empty().append('<option value="">Select District</option>');$('#city_id').prop('disabled',true).empty().append('<option value="">Select City</option>');if(sid){$.get('{{ route('admin.user-ecard-report.districts') }}',{state_id:sid},function(data){$('#district_id').prop('disabled',false);data.forEach(function(it){$('#district_id').append('<option value="'+it.id+'">'+it.district_name+'</option>');});});}});
  $('#district_id').on('change',function(){const did=$(this).val();$('#city_id').prop('disabled',true).empty().append('<option value="">Select City</option>');if(did){$.get('{{ route('admin.user-ecard-report.cities') }}',{district_id:did},function(data){$('#city_id').prop('disabled',false);data.forEach(function(it){$('#city_id').append('<option value="'+it.id+'">'+it.city_name+'</option>');});});}});
  $('#selectAll').on('change',function(){const c=$(this).is(':checked');$('#ecardReportTable').find('input.row-select').prop('checked',c);});
  table.on('draw',function(){const c=$('#selectAll').is(':checked');$('#ecardReportTable').find('input.row-select').prop('checked',c);});
})();</script>
@endpush

@extends('layouts.admin')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">G.D. Scheme User Fund</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item"><a href="#">Benefit Modules</a></li>
                    <li class="breadcrumb-item active" aria-current="page">G.D. Scheme User Fund</li>
                </ol>
            </nav>
        </div>
        <div class="head-icons ms-2">
            <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Refresh" onclick="window.location.reload();">
                <i class="ti ti-refresh-dot"></i>
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">G.D. Scheme User Fund</h5>
                </div>
                <div class="card-body">
                    <form id="fundForm" class="row g-3">
                        <div class="col-md-4">
                            <label for="scheme" class="form-label">Select Scheme</label>
                            <select id="scheme" name="scheme" class="form-select">
                                @foreach($schemes as $scheme)
                                    <option value="{{ $scheme }}">{{ $scheme }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="fund" class="form-label">Enter distributor Fund</label>
                            <input type="number" step="0.01" min="0" id="fund" name="fund" class="form-control" placeholder="0.00">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary" id="btnAddFund">Add Fund</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Scheme User Fund List</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="gdSchemeFundTable" class="table table-bordered table-striped w-100">
                            <thead>
                                <tr>
                                    <th>sl no</th>
                                    <th>scheme name</th>
                                    <th>user id</th>
                                    <th>user name</th>
                                    <th>mobile no</th>
                                    <th>eligible date</th>
                                    <th>fund</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(function() {
        var table = $('#gdSchemeFundTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('admin.benefits.gd-scheme-fund.data') }}',
                data: function(d) {
                    d.scheme = $('#scheme').val();
                }
            },
            dom: 'Bfrtip',
            buttons: ['copy', 'csv', 'excel', 'pdf'],
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'scheme_name', name: 'scheme_name' },
                { data: 'user_id', name: 'user_id' },
                { data: 'user_name', name: 'user_name' },
                { data: 'mobile_no', name: 'mobile_no' },
                { data: 'eligible_date', name: 'eligible_date' },
                { data: 'fund', name: 'fund' },
            ]
        });

        $('#fundForm').on('submit', function(e) {
            e.preventDefault();
            var btn = $('#btnAddFund');
            btn.prop('disabled', true);
            $.ajax({
                method: 'POST',
                url: '{{ route('admin.benefits.gd-scheme-fund.store') }}',
                data: {
                    _token: '{{ csrf_token() }}',
                    scheme: $('#scheme').val(),
                    fund: $('#fund').val()
                },
                success: function(res) {
                    toastr.success(res.message || 'Fund added successfully');
                    table.ajax.reload();
                    $('#fund').val('');
                },
                error: function(xhr) {
                    let msg = 'Failed to add fund';
                    if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                    toastr.error(msg);
                },
                complete: function() {
                    btn.prop('disabled', false);
                }
            });
        });

        $('#scheme').on('change', function(){
            $('#gdSchemeFundTable').DataTable().ajax.reload();
        });
    });
</script>
@endsection
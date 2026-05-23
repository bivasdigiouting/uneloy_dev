@extends('layouts.admin')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Vendor Global Disburs. Fund</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Vendor Global Disburs. Fund</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            <div class="card card-primary">
                <div class="card-header"><h3 class="card-title">Enter Distributor Fund</h3></div>
                <div class="card-body">
                    <form id="global-disburse-form" class="form-inline" onsubmit="return false;">
                        <div class="form-group mb-2 mr-2">
                            <label for="amount" class="mr-2">Amount</label>
                            <input type="number" step="0.01" min="0" id="amount" name="amount" class="form-control" placeholder="Enter distribution fund" required>
                        </div>
                        <button type="submit" class="btn btn-primary mb-2" id="btnDistribute">Distribute Fund</button>
                        <span id="distResult" class="ml-3 text-success"></span>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Vendors</h3>
                </div>
                <div class="card-body">
                    <table id="vendors-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Vendor Id</th>
                                <th>Vendor Name</th>
                                <th>Mobile No.</th>
                                <th>Fund</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
$(function(){
    var table = $('#vendors-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('admin.vendor-global-fund.data') }}',
            data: function(d){
                d.distribution_id = window.latestDistributionId || '';
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'vendor_id', name: 'vendor_id' },
            { data: 'vendor_name', name: 'vendor_name' },
            { data: 'mobile_no', name: 'mobile_no' },
            { data: 'fund', name: 'fund', orderable: false, searchable: false },
        ]
    });

    $('#global-disburse-form').on('submit', function(){
        var amount = parseFloat($('#amount').val());
        if (!amount || amount <= 0) {
            alert('Please enter a valid amount.');
            return;
        }
        $('#btnDistribute').prop('disabled', true);
        $('#distResult').removeClass('text-danger').text('Processing...');
        $.ajax({
            url: '{{ route('admin.vendor-global-fund.distribute') }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                amount: amount
            },
            success: function(res){
                if (res.success) {
                    window.latestDistributionId = res.distribution_id;
                    $('#distResult').addClass('text-success').text('Distributed successfully. Total: '+res.summary.total_amount+' to '+res.summary.vendor_count+' vendors.');
                    table.ajax.reload(null, false);
                } else {
                    $('#distResult').addClass('text-danger').text(res.message || 'Distribution failed');
                }
            },
            error: function(xhr){
                let msg = 'Distribution failed';
                try { msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : msg; } catch(e) {}
                $('#distResult').addClass('text-danger').text(msg);
            },
            complete: function(){
                $('#btnDistribute').prop('disabled', false);
            }
        });
    });
});
</script>
@endpush
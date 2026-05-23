@extends('layouts.admin')

@section('title', 'Vendor Wallet Summary')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Vendor Wallet Summary</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a></li>
                    <li class="breadcrumb-item">Vendor Management</li>
                    <li class="breadcrumb-item active" aria-current="page">Vendor Wallet Summary</li>
                </ol>
            </nav>
        </div>
        <div class="head-icons ms-2">
            <a href="{{ route('admin.vendor.wallet.management') }}" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Go to Add/Remove Vendor Wallet"><i class="ti ti-wallet"></i></a>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary ms-1" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Back to Dashboard"><i class="ti ti-arrow-left"></i></a>
        </div>
    </div>

    <div class="row"><div class="col-12"><div class="card"><div class="card-header"><h5 class="card-title mb-0">Search</h5></div><div class="card-body">
        <form id="vendorWalletSummaryFilterForm" class="row g-3">
            <div class="col-md-12">
                <label for="search_id" class="form-label">Search Id/Name/Email/Mobile</label>
                <input type="text" id="search_id" name="search_id" class="form-control" placeholder="Enter Id, Name, Email or Mobile">
            </div>
            <div class="col-12 d-flex justify-content-end mt-2">
                <button type="button" id="btnVendorWalletSummarySearch" class="btn btn-primary me-2"><i class="ti ti-search"></i> Search</button>
                <button type="button" id="btnVendorWalletSummaryReset" class="btn btn-secondary"><i class="ti ti-refresh"></i> Reset</button>
            </div>
        </form>
    </div></div></div></div>

    <div class="row"><div class="col-12"><div class="card"><div class="card-header"><h5 class="card-title mb-0">Wallet Summary List</h5></div><div class="card-body">
        <div class="table-responsive">
            <table id="vendorWalletSummaryTable" class="table table-striped table-bordered">
                <thead><tr>
                    <th>Sr. No.</th><th>Id</th><th>Name</th><th>Mobile No.</th><th>Email Id</th><th>Cr. Amount</th><th>Dr. Amount</th><th>Current Balance</th><th>Action</th>
                </tr></thead>
                <tbody></tbody>
                <tfoot><tr>
                    <th colspan="5" class="text-end">Total:</th>
                    <th id="totalCrAmount">₹0.00</th>
                    <th id="totalDrAmount">₹0.00</th>
                    <th id="totalCurrentBalance">₹0.00</th>
                    <th></th>
                </tr></tfoot>
            </table>
        </div>
    </div></div></div></div>

    <div class="modal fade" id="vendorWalletModal" tabindex="-1" aria-labelledby="vendorWalletModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered"><div class="modal-content">
            <div class="modal-header"><h5 class="modal-title" id="vendorWalletModalLabel">Vendor Wallet Details</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-4"><label class="form-label">Id</label><input type="text" class="form-control" id="modal_vendor_id" readonly></div>
                    <div class="col-md-8"><label class="form-label">Name</label><input type="text" class="form-control" id="modal_vendor_name" readonly></div>
                    <div class="col-md-6"><label class="form-label">Mobile No.</label><input type="text" class="form-control" id="modal_vendor_mobile" readonly></div>
                    <div class="col-md-6"><label class="form-label">Email Id</label><input type="text" class="form-control" id="modal_vendor_email" readonly></div>
                    <div class="col-md-12"><label class="form-label">Current Balance</label><div class="input-group"><span class="input-group-text">₹</span><input type="text" class="form-control" id="modal_vendor_balance" readonly></div></div>
                    <div class="col-12 mt-3"><h6 class="mb-2">Transaction History</h6><div id="modal_transactions_container" class="border rounded p-2 bg-light"><div class="text-muted">Loading transactions...</div></div></div>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><a href="#" id="modal_add_fund_link" class="btn btn-primary"><i class="ti ti-plus"></i> Add Fund</a></div>
        </div></div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script>
(function() {
    var table = $('#vendorWalletSummaryTable').DataTable({
        processing: true, serverSide: true, responsive: true, pageLength: 10,
        ajax: { url: '{{ route('admin.vendor.wallet.summary.data') }}', data: function (d) { var formData = $('#vendorWalletSummaryFilterForm').serializeArray(); formData.forEach(function(item) { d[item.name] = item.value; }); } },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'id', name: 'id' },
            { data: 'display_name', name: 'display_name' },
            { data: 'mobile_no', name: 'mobile_no' },
            { data: 'email_id', name: 'email_id' },
            { data: 'cr_amount', name: 'cr_amount', orderable: false, searchable: false },
            { data: 'dr_amount', name: 'dr_amount', orderable: false, searchable: false },
            { data: 'current_balance', name: 'current_balance', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        order: [[1, 'asc']]
    });

    $('#btnVendorWalletSummarySearch').on('click', function () { table.ajax.reload(); });
    $('#btnVendorWalletSummaryReset').on('click', function () { $('#vendorWalletSummaryFilterForm')[0].reset(); table.ajax.reload(); });

    $('#vendorWalletSummaryTable').on('xhr.dt', function (e, settings, json) {
        if (json && json.sum_cr !== undefined) { $('#totalCrAmount').text(json.sum_cr); }
        if (json && json.sum_dr !== undefined) { $('#totalDrAmount').text(json.sum_dr); }
        if (json && json.sum_current_balance !== undefined) { $('#totalCurrentBalance').text(json.sum_current_balance); }
    });

    $(document).on('click', '.view-vendor-wallet', function () {
        var id = $(this).data('id'), name = $(this).data('name'), mobile = $(this).data('mobile'), email = $(this).data('email'), balance = $(this).data('balance');
        $('#modal_vendor_id').val(id); $('#modal_vendor_name').val(name); $('#modal_vendor_mobile').val(mobile); $('#modal_vendor_email').val(email); $('#modal_vendor_balance').val(balance);
        var addFundUrl = '{{ route('admin.vendor.wallet.management') }}' + '?search_id=' + encodeURIComponent(id);
        $('#modal_add_fund_link').attr('href', addFundUrl);
        $('#modal_transactions_container').html('<div class="text-muted">Loading transactions...</div>');
        var baseUrl = '{{ route('admin.vendor.wallet.summary.transactions', ['vendorId' => 0]) }}'; var txUrl = baseUrl.replace('/0', '/' + encodeURIComponent(id));
        $.get(txUrl).done(function (html) { $('#modal_transactions_container').html(html); }).fail(function () { $('#modal_transactions_container').html('<div class="text-danger">Failed to load transactions.</div>'); });
        var modal = new bootstrap.Modal(document.getElementById('vendorWalletModal')); modal.show();
    });
})();
</script>
@endpush

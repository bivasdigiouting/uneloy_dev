@extends('layouts.admin')

@section('title', 'User Upload Reward Report')

@section('content')
<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h3 class="page-title">User Upload Reward Report</h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Report Modules</li>
                <li class="breadcrumb-item active">User Upload Reward</li>
            </ul>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted mb-1">Total Upload Reward</h6>
                <h3 id="totalReward">0.00</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted mb-1">Average Upload Reward</h6>
                <h3 id="avgReward">0.00</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted mb-1">Orders with Upload Reward</h6>
                <h3 id="countReward">0</h3>
            </div>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header">
        <h5 class="card-title mb-0">Filters</h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-2">
                <label class="form-label">From date</label>
                <input type="date" id="from_date" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label">To date</label>
                <input type="date" id="to_date" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label">Order No.</label>
                <input type="text" id="order_no" class="form-control" placeholder="Order no">
            </div>
            <div class="col-md-2">
                <label class="form-label">Min Reward</label>
                <input type="number" step="0.01" id="min_reward" class="form-control" placeholder="0.00">
            </div>
            <div class="col-md-2">
                <label class="form-label">Max Reward</label>
                <input type="number" step="0.01" id="max_reward" class="form-control" placeholder="0.00">
            </div>
            <div class="col-md-2">
                <label class="form-label">Search (name/email)</label>
                <input type="text" id="search_text" class="form-control" placeholder="Type to search">
            </div>
        </div>
        <div class="mt-3">
            <button class="btn btn-primary" id="btnSearch">Search</button>
            <button class="btn btn-secondary" id="btnReset">Reset</button>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped" id="rewardTable" style="width:100%">
                <thead>
                    <tr>
                        <th>Sr. No.</th>
                        <th>Order No.</th>
                        <th>Order Date</th>
                        <th>Seller ID (Name)</th>
                        <th>Purchase ID (Name)</th>
                        <th>Upload Reward Amount</th>
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
    $(function() {
        var table = $('#rewardTable').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            ajax: {
                url: '{{ route('admin.reports.user-upload-reward.data') }}',
                data: function(d) {
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                    d.order_no = $('#order_no').val();
                    d.min_reward = $('#min_reward').val();
                    d.max_reward = $('#max_reward').val();
                    d.search = { value: $('#search_text').val() };
                }
            },
            columns: [
                { data: 'sr_no', name: 'sr_no', orderable: false },
                { data: 'order_no', name: 'order_no' },
                { data: 'order_date', name: 'order_date' },
                { data: 'seller', name: 'seller', orderable: false },
                { data: 'buyer', name: 'buyer', orderable: false },
                { data: 'upload_reward_amount', name: 'upload_reward_amount' }
            ],
            order: [[2, 'desc']],
            drawCallback: function(settings) {
                var json = settings.json || {};
                if (json.summary) {
                    $('#totalReward').text(json.summary.total_reward || '0.00');
                    $('#avgReward').text(json.summary.average_reward || '0.00');
                    $('#countReward').text(json.summary.count_reward || '0');
                }

                // Append totals row
                var api = this.api();
                var footerHtml = '';
                if (json.summary && json.summary.total_reward) {
                    footerHtml = '<tr class="table-secondary">' +
                        '<td colspan="5" class="text-end"><strong>Total Upload Reward</strong></td>' +
                        '<td><strong>' + json.summary.total_reward + '</strong></td>' +
                        '</tr>';
                }
                $('#rewardTable tbody').append(footerHtml);
            }
        });

        $('#btnSearch').on('click', function() { table.draw(); });
        $('#btnReset').on('click', function() {
            $('#from_date, #to_date, #order_no, #min_reward, #max_reward, #search_text').val('');
            table.draw();
        });
    });
</script>
@endpush
@extends('layouts.admin')

@section('page_title', 'User Reward Report')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-sm-12">
            <h4 class="page-title">User Reward Report</h4>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Reward</h5>
                    <h3 id="summary-total">0.00</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Average Reward</h5>
                    <h3 id="summary-average">0.00</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Orders with Reward</h5>
                    <h3 id="summary-count">0</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="from_date" class="form-label">From Date</label>
                    <input type="date" class="form-control" id="from_date">
                </div>
                <div class="col-md-3">
                    <label for="to_date" class="form-label">To Date</label>
                    <input type="date" class="form-control" id="to_date">
                </div>
                <div class="col-md-2">
                    <label for="order_no" class="form-label">Order No.</label>
                    <input type="text" class="form-control" id="order_no" placeholder="Order No">
                </div>
                <div class="col-md-2">
                    <label for="min_reward" class="form-label">Min Reward</label>
                    <input type="number" class="form-control" id="min_reward" step="0.01" placeholder="0">
                </div>
                <div class="col-md-2">
                    <label for="max_reward" class="form-label">Max Reward</label>
                    <input type="number" class="form-control" id="max_reward" step="0.01" placeholder="0">
                </div>
                <div class="col-md-4">
                    <label for="search" class="form-label">Search (name, email, order)</label>
                    <input type="text" class="form-control" id="search" placeholder="Type and press Enter">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100" id="btnSearch">Search</button>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-secondary w-100" id="btnReset">Reset</button>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table id="rewardTable" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>Sr. No.</th>
                        <th>Order No.</th>
                        <th>Order Date</th>
                        <th>Seller</th>
                        <th>Buyer</th>
                        <th>Reward Amount</th>
                    </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" class="text-end">Total Reward:</th>
                        <th id="footer-total">0.00</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const table = $('#rewardTable').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        ajax: {
            url: "{{ route('admin.reports.user-reward.data') }}",
            data: function (d) {
                d.from_date = document.getElementById('from_date').value;
                d.to_date = document.getElementById('to_date').value;
                d.order_no = document.getElementById('order_no').value;
                d.min_reward = document.getElementById('min_reward').value;
                d.max_reward = document.getElementById('max_reward').value;
                d.search = document.getElementById('search').value;
            }
        },
        columns: [
            { data: 'sr_no', name: 'sr_no' },
            { data: 'order_no', name: 'o.order_no' },
            { data: 'order_date', name: 'o.order_date' },
            { data: 'seller', name: 'seller.name' },
            { data: 'buyer', name: 'buyer.name' },
            { data: 'reward_amount', name: 'reward_amount', className: 'text-end' },
        ],
        drawCallback: function (settings) {
            const json = settings.json || {};
            const summary = json.summary || { total: '0.00', average: '0.00', count: 0 };
            document.getElementById('summary-total').innerText = summary.total;
            document.getElementById('summary-average').innerText = summary.average;
            document.getElementById('summary-count').innerText = summary.count;
            document.getElementById('footer-total').innerText = summary.total;
        }
    });

    document.getElementById('btnSearch').addEventListener('click', () => table.ajax.reload());
    document.getElementById('btnReset').addEventListener('click', () => {
        ['from_date','to_date','order_no','min_reward','max_reward','search'].forEach(id => document.getElementById(id).value = '');
        table.ajax.reload();
    });

    document.getElementById('search').addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            table.ajax.reload();
        }
    });
});
</script>
@endsection
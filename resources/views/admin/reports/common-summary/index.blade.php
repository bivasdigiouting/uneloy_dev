@extends('layouts.admin')

@section('content')

    <div class="content">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Common Summary Report</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Report Modules</li>
                        <li class="breadcrumb-item active">Common Summary Report</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">From Date</label>
                        <input type="date" class="form-control" id="from_date" />
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">To Date</label>
                        <input type="date" class="form-control" id="to_date" />
                    </div>
                    <div class="col-md-6 text-end">
                        <button class="btn btn-primary" id="btnFilter"><i class="ti ti-filter"></i> Apply Filters</button>
                        <button class="btn btn-light" id="btnReset"><i class="ti ti-refresh"></i> Reset</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="summaryCards">
            <!-- Cards will be injected here -->
        </div>

        <div class="card mt-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Status Breakdowns</h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6>Wallet Requests</h6>
                        <div id="walletBreakdown" class="d-flex gap-2 flex-wrap"></div>
                    </div>
                    <div class="col-md-6">
                        <h6>A & R Stock Requests</h6>
                        <div id="arBreakdown" class="d-flex gap-2 flex-wrap"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@push('scripts')
<script>
    function currency(amount) {
        try {
            const num = parseFloat(amount || 0);
            return '₹' + num.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        } catch (e) { return '₹' + amount; }
    }

    function renderCards(cards) {
        const container = document.getElementById('summaryCards');
        container.innerHTML = '';
        const colorMap = ['primary','success','warning','info','danger','secondary','purple','pink'];
        cards.forEach((c, idx) => {
            const isMoney = String(c.key).includes('amount');
            const val = isMoney ? currency(c.value) : c.value;
            const color = colorMap[idx % colorMap.length];
            const col = document.createElement('div');
            col.className = 'col-md-3';
            col.innerHTML = `
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-muted fs-12">${c.label}</div>
                                <div class="fs-4 fw-bold">${val}</div>
                            </div>
                            <div class="avatar bg-${color} text-white">
                                <i class="ti ti-chart-bar"></i>
                            </div>
                        </div>
                    </div>
                </div>`;
            container.appendChild(col);
        });
    }

    function renderBreakdown(containerId, breakdown, isMoneyKeys = []) {
        const container = document.getElementById(containerId);
        container.innerHTML = '';
        Object.entries(breakdown).forEach(([key, val]) => {
            const label = key.charAt(0).toUpperCase() + key.slice(1);
            const display = isMoneyKeys.includes(key) ? currency(val) : val;
            const badge = document.createElement('span');
            badge.className = 'badge bg-light text-dark p-2';
            badge.innerHTML = `<strong>${label}:</strong> ${display}`;
            container.appendChild(badge);
        });
    }

    async function loadSummary() {
        const from = document.getElementById('from_date').value;
        const to = document.getElementById('to_date').value;
        const params = new URLSearchParams();
        if (from) params.append('from_date', from);
        if (to) params.append('to_date', to);
        const res = await fetch(`{{ route('admin.reports.common-summary.data') }}?` + params.toString());
        const data = await res.json();
        renderCards(data.cards || []);
        // Wallet breakdowns
        renderBreakdown('walletBreakdown', data.breakdowns?.wallet_status || {});
        // A&R breakdowns (status only)
        renderBreakdown('arBreakdown', data.breakdowns?.ar_request_status || {});
    }

    document.getElementById('btnFilter').addEventListener('click', loadSummary);
    document.getElementById('btnReset').addEventListener('click', () => {
        document.getElementById('from_date').value = '';
        document.getElementById('to_date').value = '';
        loadSummary();
    });

    // Initial load
    loadSummary();
</script>
@endpush
@endsection
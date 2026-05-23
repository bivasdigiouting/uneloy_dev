@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h3 class="page-title">Stock Transfer Report</h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Stock Management</li>
                <li class="breadcrumb-item active">Stock Transfer Report</li>
            </ul>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Filter</h5>
            </div>
            <div class="card-body">
                <form id="report-filter-form" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">From Date</label>
                        <input type="date" id="from_date" name="from_date" class="form-control" />
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">To Date</label>
                        <input type="date" id="to_date" name="to_date" class="form-control" />
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Level Name</label>
                        <select id="level" name="level" class="form-select">
                            <option value="">Select Level</option>
                            <option value="state">State Member</option>
                            <option value="district">District Member</option>
                            <option value="city">City Member</option>
                            <option value="panchayat">Panchayat Member</option>
                            <option value="village">Village Member</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Member</label>
                        <div id="member-container">
                            <!-- Dynamic controls based on level selection -->
                            <select id="member_id" name="member_id" class="form-select" style="display:none;"></select>
                            <input type="text" id="member_name" name="member_name" class="form-control" placeholder="Enter name" style="display:none;" />
                        </div>
                    </div>

                    <div class="col-12 d-flex gap-2">
                        <button type="button" id="apply-filter" class="btn btn-primary">Apply Filter</button>
                        <button type="reset" id="reset-filter" class="btn btn-light">Reset</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Transfers</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="stock-transfer-report-table" class="table table-striped table-bordered w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Category</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const reportRoutes = {
        data: "{{ route('admin.stock-transfers.report.data') }}",
        apiStates: "{{ url('/api/location/states') }}",
        apiDistricts: function(stateId) { return "{{ url('/api/location/districts') }}" + "?state_id=" + stateId; },
        apiCitiesByState: function(stateId) { return "{{ url('/api/location/cities-by-state') }}" + "?state_id=" + stateId; },
        apiCities: function(districtId) { return "{{ url('/api/location/cities') }}" + "?district_id=" + districtId; }
    };

    function populateStates($el) {
        $.getJSON(reportRoutes.apiStates, function(res) {
            const list = res.data || res;
            $el.empty().append('<option value="">Select State</option>');
            list.forEach(s => $el.append(`<option value="${s.id}">${s.state_name || s.name}</option>`));
        });
    }
    function populateDistricts(stateId, $el) {
        $el.empty().append('<option value="">Select District</option>');
        if (!stateId) return;
        $.getJSON(reportRoutes.apiDistricts(stateId), function(res) {
            const list = res.data || res;
            list.forEach(d => $el.append(`<option value="${d.id}">${d.district_name || d.name}</option>`));
        });
    }
    function populateCitiesByState(stateId, $el) {
        $el.empty().append('<option value="">Select City</option>');
        if (!stateId) return;
        $.getJSON(reportRoutes.apiCitiesByState(stateId), function(res) {
            const list = res.data || res;
            list.forEach(c => $el.append(`<option value="${c.id}">${c.city_name || c.name}</option>`));
        });
    }

    function renderMemberControl(level) {
        const $memberId = $('#member_id');
        const $memberName = $('#member_name');
        // Clear and hide both by default
        $memberId.hide();
        $memberName.hide();
        $memberId.empty();
        $memberName.val('');

        // Remove any extra dependent selects
        $('#state_selector_wrapper').remove();
        $('#district_selector_wrapper').remove();

        if (!level) return;

        if (level === 'state') {
            // Single dropdown of states
            $memberId.show();
            $memberId.attr('placeholder', 'Select State');
            populateStates($memberId);
        } else if (level === 'district') {
            // State selector + district selector
            const stateSelect = $('<select class="form-select" id="state_selector"></select>');
            const stateWrapper = $('<div id="state_selector_wrapper" class="mb-2"></div>').append('<label class="form-label">State</label>').append(stateSelect);
            $('#member-container').prepend(stateWrapper);
            populateStates(stateSelect);

            $memberId.show();
            $memberId.attr('placeholder', 'Select District');
            stateSelect.on('change', function() {
                populateDistricts(this.value, $memberId);
            });
        } else if (level === 'city') {
            // State selector + city selector (by state)
            const stateSelect = $('<select class="form-select" id="state_selector"></select>');
            const stateWrapper = $('<div id="state_selector_wrapper" class="mb-2"></div>').append('<label class="form-label">State</label>').append(stateSelect);
            $('#member-container').prepend(stateWrapper);
            populateStates(stateSelect);

            $memberId.show();
            $memberId.attr('placeholder', 'Select City');
            stateSelect.on('change', function() {
                populateCitiesByState(this.value, $memberId);
            });
        } else if (level === 'panchayat' || level === 'village') {
            // Free text name filter
            $memberName.attr('placeholder', level === 'panchayat' ? 'Enter Panchayat Name' : 'Enter Village Name');
            $memberName.show();
        }
    }

    $(document).ready(function() {
        // Level change -> render appropriate member control
        $('#level').on('change', function() {
            renderMemberControl(this.value);
        });

        // Initialize DataTable
        const table = $('#stock-transfer-report-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: reportRoutes.data,
                data: function(d) {
                    d.from_date = $('#from_date').val() || '';
                    d.to_date = $('#to_date').val() || '';
                    d.level = $('#level').val() || '';
                    // If ID-based selection is visible, pass its value
                    const memberIdVisible = $('#member_id').is(':visible');
                    const memberNameVisible = $('#member_name').is(':visible');
                    d.member_id = memberIdVisible ? ($('#member_id').val() || '') : '';
                    d.member_name = memberNameVisible ? ($('#member_name').val() || '') : '';
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'category', name: 'category' },
                { data: 'product', name: 'product' },
                { data: 'quantity', name: 'quantity' },
                { data: 'from_level', name: 'from_level' },
                { data: 'to_level', name: 'to_level' },
                { data: 'created_at', name: 'created_at' },
            ]
        });

        // Apply filter
        $('#apply-filter').on('click', function() {
            table.ajax.reload();
        });

        // Reset filter -> clear controls and reload
        $('#reset-filter').on('click', function() {
            // Allow form reset to clear values
            setTimeout(function() {
                renderMemberControl('');
                table.ajax.reload();
            }, 50);
        });
    });
</script>
@endpush
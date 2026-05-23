@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Global Disbursement Level Fund</h5>
            </div>
            <div class="card-body">
                <form id="distribute-form" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Enter Distribute Fund <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0.01" class="form-control" id="amount" name="amount" placeholder="Enter total amount" required />
                    </div>
                    <div class="col-md-8 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary">Distribute Fund</button>
                        <button type="reset" class="btn btn-light">Reset</button>
                    </div>
                </form>
                <div id="distribution-summary" class="mt-3" style="display:none;"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Level Users & Fund</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="eps-level-fund-table" class="table table-striped table-bordered w-100">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Level</th>
                                <th>User Id</th>
                                <th>User Name</th>
                                <th>Mobile No.</th>
                                <th>Fund</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const routes = {
    data: "{{ route('admin.eps-level-fund.data') }}",
    distribute: "{{ route('admin.eps-level-fund.distribute') }}",
};

let currentDistributionId = null;

$(document).ready(function() {
    // Initialize DataTable
    const table = $('#eps-level-fund-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: routes.data,
            data: function(d) {
                d.distribution_id = currentDistributionId || '';
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'level', name: 'level' },
            { data: 'user_id', name: 'user_id' },
            { data: 'user_name', name: 'user_name' },
            { data: 'mobile_no', name: 'mobile_no' },
            { data: 'fund', name: 'fund' },
        ],
        order: [[1, 'asc']]
    });

    // Handle distribute form submit
    $('#distribute-form').on('submit', function(e) {
        e.preventDefault();
        const formData = $(this).serialize();
        $.ajax({
            url: routes.distribute,
            method: 'POST',
            data: formData,
            success: function(res) {
                if (res.success) {
                    currentDistributionId = res.distribution_id;
                    // Show summary
                    const s = res.summary;
                    const html = `
                        <div class="alert alert-success">
                            <strong>Distributed:</strong> Rs. ${Number(s.total_amount).toFixed(2)}<br/>
                            <strong>Level Allocations:</strong>
                            <ul class="mb-0">
                                <li>State: Rs. ${Number(s.level_allocations.state_level).toFixed(2)} (Per user: Rs. ${Number(s.per_user_allocations.state_level).toFixed(2)})</li>
                                <li>District: Rs. ${Number(s.level_allocations.district_level).toFixed(2)} (Per user: Rs. ${Number(s.per_user_allocations.district_level).toFixed(2)})</li>
                                <li>Block: Rs. ${Number(s.level_allocations.block_level).toFixed(2)} (Per user: Rs. ${Number(s.per_user_allocations.block_level).toFixed(2)})</li>
                                <li>Panchayat: Rs. ${Number(s.level_allocations.panchayat_level).toFixed(2)} (Per user: Rs. ${Number(s.per_user_allocations.panchayat_level).toFixed(2)})</li>
                                <li>Village: Rs. ${Number(s.level_allocations.village_level).toFixed(2)} (Per user: Rs. ${Number(s.per_user_allocations.village_level).toFixed(2)})</li>
                            </ul>
                        </div>`;
                    $('#distribution-summary').html(html).show();
                    table.ajax.reload();
                } else {
                    alert(res.message || 'Distribution failed');
                }
            },
            error: function(xhr) {
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = xhr.responseJSON.errors;
                    let msg = 'Please correct errors: ';
                    Object.keys(errors).forEach(k => { msg += `\n- ${k}: ${errors[k].join(', ')}`; });
                    alert(msg);
                } else {
                    alert('Server error while distributing fund');
                }
            }
        });
    });
});
</script>
@endpush
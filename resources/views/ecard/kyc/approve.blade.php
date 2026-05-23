@extends('ecard.ecard')

@section('title', 'Approve KYC Documents')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <h5 class="mb-0">Approve KYC Documents</h5>
            <small class="text-muted">Filter and review user KYC document status</small>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form id="filtersForm" class="row g-3">
                <div class="col-md-3">
                    <label for="criteria" class="form-label">Criteria</label>
                    <select id="criteria" name="criteria" class="form-select">
                        @foreach($criteriaOptions as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select id="status" name="status" class="form-select">
                        @foreach($statusOptions as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3" id="memberIdWrap">
                    <label for="member_id" class="form-label">Member ID</label>
                    <input type="text" id="member_id" name="member_id" class="form-control" placeholder="Enter member ID">
                </div>
                <div class="col-md-3" id="searchTextWrap">
                    <label for="search_text" class="form-label">Search</label>
                    <input type="text" id="search_text" name="search_text" class="form-control" placeholder="Name, email, mobile or ID">
                </div>
                <div class="col-md-3 d-none" id="fromDateWrap">
                    <label for="from_date" class="form-label">From date</label>
                    <input type="date" id="from_date" name="from_date" class="form-control">
                </div>
                <div class="col-md-3 d-none" id="toDateWrap">
                    <label for="to_date" class="form-label">To date</label>
                    <input type="date" id="to_date" name="to_date" class="form-control">
                </div>
                <div class="col-md-12 d-flex justify-content-end">
                    <button type="button" id="applyFilters" class="btn btn-primary">
                        <i class="fa fa-filter me-1"></i> Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="kycTable" class="table table-sm table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Sr. No</th>
                            <th>Member No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>PAN</th>
                            <th>Aadhaar</th>
                            <th>Bank</th>
                            <th>Account</th>
                            <th>IFSC</th>
                            <th>Status</th>
                            <th>Joining</th>
                            <th>Last Update</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    const dataUrl = "{{ route('ecard.kyc.approve.data') }}";

    let dt = null;
    
    function criteriaToggles() {
        const val = document.getElementById('criteria').value;
        const memberWrap = document.getElementById('memberIdWrap');
        const searchWrap = document.getElementById('searchTextWrap');
        const fromWrap = document.getElementById('fromDateWrap');
        const toWrap = document.getElementById('toDateWrap');
        if (val === 'id_no') {
            memberWrap.classList.remove('d-none');
            fromWrap.classList.add('d-none');
            toWrap.classList.add('d-none');
        } else if (['joining_date','approve_reject_date','upload_date'].includes(val)) {
            memberWrap.classList.add('d-none');
            fromWrap.classList.remove('d-none');
            toWrap.classList.remove('d-none');
        } else {
            memberWrap.classList.add('d-none');
            fromWrap.classList.add('d-none');
            toWrap.classList.add('d-none');
        }
        searchWrap.classList.remove('d-none');
    }

    function loadTable() {
        if (dt) {
            dt.destroy();
            document.querySelector('#kycTable tbody').innerHTML = '';
        }
        dt = $('#kycTable').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            order: [[11, 'desc']],
            language: {
                emptyTable: 'No KYC records found for the selected filters.'
            },
            ajax: {
                url: dataUrl,
                data: function (d) {
                    d.criteria = $('#criteria').val();
                    d.status = $('#status').val();
                    d.member_id = $('#member_id').val();
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                    d.search_text = $('#search_text').val();
                }
            },
            columns: [
                { data: null, name: 'srno', orderable: false, searchable: false,
                  render: function (data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; } },
                { data: 'member_no', name: 'member_no' },
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'mobile_no', name: 'mobile_no' },
                { data: 'pan_no', name: 'pan_no' },
                { data: 'aadhaar_no', name: 'aadhaar_no' },
                { data: 'bank_name', name: 'bank_name' },
                { data: 'account_no', name: 'account_no' },
                { data: 'ifsc_code', name: 'ifsc_code' },
                { data: 'kyc_status', name: 'kyc_status', orderable: false, render: function (data) {
                    const cls = data === 'Uploaded All Docs' ? 'bg-success' : (data === 'Rejected' ? 'bg-danger' : 'bg-warning text-dark');
                    return `<span class="badge ${cls}">${data}</span>`;
                } },
                { data: 'created_at', name: 'created_at' },
                { data: 'updated_at', name: 'updated_at' },
            ]
        });
    }

    $(document).ready(function () {
        criteriaToggles();
        loadTable();

        $('#criteria').on('change', function () { criteriaToggles(); });
        $('#applyFilters').on('click', function () { loadTable(); });
    });
</script>
@endsection
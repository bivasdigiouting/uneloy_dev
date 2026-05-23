@extends('layouts.admin')

@section('title', 'Approve KYC Document')

@section('content')
<div class="pagetitle">
    <h1>Approve KYC Document</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item">E-Card Seva Modules</li>
            <li class="breadcrumb-item active">Approve KYC Document</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Filters</h5>

            <form id="filterForm" class="row g-3">
                <div class="col-md-3">
                    <label for="criteria" class="form-label">Select Criteria</label>
                    <select id="criteria" name="criteria" class="form-select">
                        @foreach($criteriaOptions as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="status" class="form-label">Select Status</label>
                    <select id="status" name="status" class="form-select">
                        @foreach($statusOptions as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 criteria-member" id="memberIdBox">
                    <label for="member_id" class="form-label">Member ID No</label>
                    <input type="text" class="form-control" id="member_id" name="member_id" placeholder="Enter Member ID" />
                </div>

                <div class="col-md-3 criteria-dates d-none" id="fromDateBox">
                    <label for="from_date" class="form-label">From Date</label>
                    <input type="date" class="form-control" id="from_date" name="from_date" />
                </div>

                <div class="col-md-3 criteria-dates d-none" id="toDateBox">
                    <label for="to_date" class="form-label">To Date</label>
                    <input type="date" class="form-control" id="to_date" name="to_date" />
                </div>

                <div class="col-md-3">
                    <label for="search_text" class="form-label">Search</label>
                    <input type="text" class="form-control" id="search_text" name="search_text" placeholder="ID / Name / Email" />
                </div>

                <div class="col-12">
                    <button type="button" id="applyFilter" class="btn btn-primary">Search</button>
                    <button type="button" id="showAll" class="btn btn-secondary">Show All</button>
                    <button type="button" id="exportExcel" class="btn btn-success">Export to Excel</button>
                </div>
            </form>

            <hr />

            <table id="kycDocumentsTable" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>Sr. No.</th>
                        <th>Member ID</th>
                        <th>Member Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>PAN No</th>
                        <th>Aadhar No</th>
                        <th>Bank Name</th>
                        <th>Account No</th>
                        <th>IFSC Code</th>
                        <th>KYC Status</th>
                        <th>Joining Date</th>
                        <th>Last Update Date</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    function toggleCriteriaInputs() {
        const criteria = document.getElementById('criteria').value;
        const memberIdBox = document.getElementById('memberIdBox');
        const fromDateBox = document.getElementById('fromDateBox');
        const toDateBox = document.getElementById('toDateBox');

        if (criteria === 'id_no') {
            memberIdBox.classList.remove('d-none');
            fromDateBox.classList.add('d-none');
            toDateBox.classList.add('d-none');
        } else {
            memberIdBox.classList.add('d-none');
            toDateBox.classList.remove('d-none');
            fromDateBox.classList.remove('d-none');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        toggleCriteriaInputs();

        const table = $('#kycDocumentsTable').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            ajax: {
                url: '{{ route('admin.ecard-seva-approve-kyc-documents.data') }}',
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
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'member_id', name: 'member_id' },
                { data: 'member_name', name: 'member_name' },
                { data: 'email', name: 'email' },
                { data: 'mobile_no', name: 'mobile_no' },
                { data: 'pan_no', name: 'pan_no' },
                { data: 'aadhaar_no', name: 'aadhaar_no' },
                { data: 'bank_name', name: 'bank_name' },
                { data: 'account_no', name: 'account_no' },
                { data: 'ifsc_code', name: 'ifsc_code' },
                { data: 'kyc_status', name: 'kyc_status', orderable: false, searchable: false },
                { data: 'created_at', name: 'created_at' },
                { data: 'updated_at', name: 'updated_at' },
            ],
            order: [[12, 'desc']]
        });

        $('#criteria').on('change', function(){
            toggleCriteriaInputs();
        });

        $('#applyFilter').on('click', function(){
            table.ajax.reload();
        });

        $('#showAll').on('click', function(){
            $('#filterForm')[0].reset();
            $('#criteria').val('id_no').trigger('change');
            table.ajax.reload();
        });

        $('#exportExcel').on('click', function(){
            const params = new URLSearchParams({
                criteria: $('#criteria').val(),
                status: $('#status').val(),
                member_id: $('#member_id').val(),
                from_date: $('#from_date').val(),
                to_date: $('#to_date').val(),
                search_text: $('#search_text').val(),
            });
            window.location.href = '{{ route('admin.ecard-seva-approve-kyc-documents.export') }}' + '?' + params.toString();
        });
    });
</script>
@endpush
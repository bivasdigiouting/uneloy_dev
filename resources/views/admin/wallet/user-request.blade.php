@extends('layouts.admin')

@section('title', 'User Wallet Request')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">User Wallet Request</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">User Management</li>
                    <li class="breadcrumb-item active" aria-current="page">User Wallet Request</li>
                </ol>
            </nav>
        </div>
        <div class="head-icons ms-2">
            <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Refresh" onclick="window.location.reload();">
                <i class="ti ti-refresh-dot"></i>
            </a>
        </div>
    </div>
    <!-- /Breadcrumb -->

    <!-- Filters + Totals + Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-end g-3">
                        <div class="col-6 col-md-2">
                            <label for="from_date" class="form-label">From Date</label>
                            <input type="date" class="form-control" id="from_date">
                        </div>
                        <div class="col-6 col-md-2">
                            <label for="to_date" class="form-label">To Date</label>
                            <input type="date" class="form-control" id="to_date">
                        </div>
                        <div class="col-6 col-md-2">
                            <label for="request_status" class="form-label">Request Status</label>
                            <select id="request_status" class="form-select">
                                <option value="">All</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="search_by" class="form-label">Search (ID / Email / Mobile)</label>
                            <input type="text" class="form-control" id="search_by" placeholder="e.g. 123 or user@example.com or 9876543210">
                        </div>
                        <div class="col-12 col-md-2 d-flex align-items-end gap-2">
                            <button id="applyFilters" type="button" class="btn btn-primary w-100">Search</button>
                            <button id="resetFilters" type="button" class="btn btn-outline-secondary w-100">Reset</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Totals -->
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-4">
                            <div class="p-3 rounded border bg-light">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="text-muted">Pending Amount</span>
                                    <span id="totalPending" class="fw-bold">0.00</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="p-3 rounded border bg-light">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="text-muted">Approved Amount</span>
                                    <span id="totalApproved" class="fw-bold">0.00</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="p-3 rounded border bg-light">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="text-muted">Rejected Amount</span>
                                    <span id="totalRejected" class="fw-bold">0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table id="userWalletRequestsTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Action</th>
                                    <th>Id No</th>
                                    <th>Name</th>
                                    <th>Amount</th>
                                    <th>Payment Status</th>
                                    <th>Transaction Id</th>
                                    <th>Remark</th>
                                    <th>Req. Date</th>
                                    <th>Admin Remark</th>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const table = $('#userWalletRequestsTable').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            order: [[8, 'desc']],
            ajax: {
                url: '{{ route('admin.user-wallet-request.data') }}',
                data: function (d) {
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                    d.request_status = $('#request_status').val();
                    d.search_by = $('#search_by').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
                { data: 'id_no', name: 'id_no' },
                { data: 'name', name: 'name' },
                { data: 'amount', name: 'amount' },
                { data: 'payment_status', name: 'payment_status' },
                { data: 'transaction_id', name: 'transaction_id' },
                { data: 'remark', name: 'remark' },
                { data: 'req_date', name: 'req_date' },
                { data: 'admin_remark', name: 'admin_remark' }
            ]
        });

        $('#userWalletRequestsTable').on('xhr.dt', function (e, settings, json, xhr) {
            if (json && json.totals) {
                $('#totalPending').text(json.totals.pending ?? '0.00');
                $('#totalApproved').text(json.totals.approved ?? '0.00');
                $('#totalRejected').text(json.totals.rejected ?? '0.00');
            }
        });

        $('#applyFilters').on('click', function () { table.ajax.reload(); });

        $('#resetFilters').on('click', function () {
            $('#from_date').val('');
            $('#to_date').val('');
            $('#request_status').val('');
            $('#search_by').val('');
            table.ajax.reload();
        });

        const baseUrl = @json(url('admin/user-wallet-request'));
        const csrfToken = @json(csrf_token());

        async function postAction(url, payload) {
            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload || {})
            });
            const json = await res.json().catch(() => ({}));
            if (!res.ok || json.success === false) {
                throw new Error(json.message || 'Request failed');
            }
            return json;
        }

        async function getAction(url) {
            const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => ({}));
            if (!res.ok || json.success === false) {
                throw new Error(json.message || 'Request failed');
            }
            return json;
        }

        function buildUrl(source, id, action) {
            const s = encodeURIComponent(source);
            const i = encodeURIComponent(id);
            if (!action) return `${baseUrl}/${s}/${i}`;
            return `${baseUrl}/${s}/${i}/${action}`;
        }

        async function promptRemark(title) {
            if (typeof Swal === 'undefined') {
                const remark = window.prompt(`${title}\n\nAdmin Remark (optional):`, '');
                return remark === null ? null : (remark || '');
            }
            const result = await Swal.fire({
                title,
                input: 'textarea',
                inputLabel: 'Admin Remark (optional)',
                inputPlaceholder: 'Write a remark (optional)',
                showCancelButton: true,
                confirmButtonText: 'Submit',
                cancelButtonText: 'Cancel'
            });
            if (!result.isConfirmed) return null;
            return (result.value || '').trim();
        }

        function notifySuccess(message) {
            if (typeof Swal === 'undefined') {
                alert(message);
                return;
            }
            Swal.fire({ icon: 'success', title: 'Success', text: message, timer: 1800, showConfirmButton: false });
        }

        function notifyError(message) {
            if (typeof Swal === 'undefined') {
                alert(message);
                return;
            }
            Swal.fire({ icon: 'error', title: 'Error', text: message });
        }

        $('#userWalletRequestsTable').on('click', '.js-wr-view', async function () {
            const id = this.getAttribute('data-id');
            const source = this.getAttribute('data-source');
            try {
                const json = await getAction(buildUrl(source, id));
                const d = json.data || {};
                const html = `
                    <div class="text-start">
                        <div><strong>Source:</strong> ${d.source || ''}</div>
                        <div><strong>ID:</strong> ${d.id || ''}</div>
                        <div><strong>Id No:</strong> ${d.id_no || ''}</div>
                        <div><strong>Name:</strong> ${d.name || ''}</div>
                        <div><strong>Amount:</strong> ${d.amount || ''}</div>
                        <div><strong>Status:</strong> ${d.status || ''}</div>
                        <div><strong>Transaction ID:</strong> ${d.transaction_id || ''}</div>
                        <div><strong>Remark:</strong> ${d.remark || ''}</div>
                        <div><strong>Admin Remark:</strong> ${d.admin_remark || ''}</div>
                        <div><strong>Req Date:</strong> ${d.req_date || ''}</div>
                    </div>
                `;
                if (typeof Swal === 'undefined') {
                    alert(`ID: ${d.id}\nStatus: ${d.status}\nAmount: ${d.amount}`);
                    return;
                }
                await Swal.fire({ title: 'Wallet Request', html, width: 720 });
            } catch (e) {
                notifyError(e.message);
            }
        });

        $('#userWalletRequestsTable').on('click', '.js-wr-approve', async function () {
            const id = this.getAttribute('data-id');
            const source = this.getAttribute('data-source');
            try {
                const remark = await promptRemark('Approve this wallet request?');
                if (remark === null) return;
                const json = await postAction(buildUrl(source, id, 'approve'), { admin_remark: remark });
                notifySuccess(json.message || 'Approved');
                table.ajax.reload(null, false);
            } catch (e) {
                notifyError(e.message);
            }
        });

        $('#userWalletRequestsTable').on('click', '.js-wr-reject', async function () {
            const id = this.getAttribute('data-id');
            const source = this.getAttribute('data-source');
            try {
                const remark = await promptRemark('Reject this wallet request?');
                if (remark === null) return;
                const json = await postAction(buildUrl(source, id, 'reject'), { admin_remark: remark });
                notifySuccess(json.message || 'Rejected');
                table.ajax.reload(null, false);
            } catch (e) {
                notifyError(e.message);
            }
        });
    });
</script>
@endpush

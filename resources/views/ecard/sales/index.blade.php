@extends('ecard.ecard')

@section('title', 'Sales List')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Sales List</h4>
        <a href="{{ route('ecard.sales.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> New Sale</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="sales-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Member Name</th>
                            <th>Sub Total</th>
                            <th>Tax</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data loaded via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(function() {
    $('#sales-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("ecard.sales.index") }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'billing_date', name: 'billing_date' },
            { data: 'customer_name', name: 'customer_name' },
            { data: 'purchase_value', name: 'purchase_value' },
            { data: 'tax_amount', name: 'tax_amount' },
            { data: 'total_amount', name: 'total_amount' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ]
    });
});
</script>
@endpush
@endsection

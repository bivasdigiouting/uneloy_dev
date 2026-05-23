@extends('layouts.admin')

@section('title', 'Expense Bill Report')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-7 d-flex align-items-center">
                    <h1 class="mb-0 me-2"><i class="ti ti-file-invoice me-1"></i> Expense Bill Report</h1>
                    <span class="badge bg-primary">{{ $bills->count() }} records</span>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.expense-bills.report') }}" class="mb-3">
                        <div class="d-flex flex-wrap align-items-end gap-2">
                            <div class="me-2">
                                <label class="form-label mb-0">Expense</label>
                                <select name="expense_id" class="form-control select2" style="min-width:220px">
                                    <option value="">All</option>
                                    @foreach($expenses as $exp)
                                        <option value="{{ $exp->id }}" {{ ($expenseId == $exp->id) ? 'selected' : '' }}>{{ $exp->expense_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="me-2">
                                <label class="form-label mb-0">Payment Mode</label>
                                <select name="payment_mode" class="form-control select2" style="min-width:160px">
                                    <option value="">All</option>
                                    <option value="cash" {{ $paymentMode === 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="bank" {{ $paymentMode === 'bank' ? 'selected' : '' }}>Bank</option>
                                    <option value="upi" {{ $paymentMode === 'upi' ? 'selected' : '' }}>UPI</option>
                                </select>
                            </div>
                            <div class="me-2">
                                <label class="form-label mb-0">Supplier</label>
                                <input type="text" name="supplier" class="form-control" value="{{ $supplier }}" placeholder="Supplier" style="min-width:180px">
                            </div>
                            <div class="me-2">
                                <label class="form-label mb-0">Start Date</label>
                                <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                            </div>
                            <div class="me-2">
                                <label class="form-label mb-0">End Date</label>
                                <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                            </div>
                            <div class="ms-auto">
                                <button type="submit" class="btn btn-secondary">Filter</button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Expense</th>
                                    <th>Bill No</th>
                                    <th>Supplier</th>
                                    <th>Payment Mode</th>
                                    <th class="text-end">Amount</th>
                                    <th>Bill</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bills as $b)
                                    <tr>
                                        <td>{{ $b->id }}</td>
                                        <td>{{ optional($b->date)->format('Y-m-d') }}</td>
                                        <td>{{ $b->expense->expense_name ?? '—' }}</td>
                                        <td>{{ $b->bill_no }}</td>
                                        <td>{{ $b->supplier }}</td>
                                        <td>{!! $b->payment_mode_badge !!}</td>
                                        <td class="text-end">{{ number_format($b->amount, 2) }}</td>
                                        <td>
                                            @if($b->bill_file)
                                                <a href="{{ asset('storage/'.$b->bill_file) }}" target="_blank" class="btn btn-sm btn-outline-primary">View</a>
                                            @else
                                                —
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Totals</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th class="text-end">{{ number_format($total, 2) }}</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection


<div class="row">
    <div class="col-12">
        <div class="page-header">
            <h4 class="page-title mb-0">BBPS Bills</h4>
        </div>

        <div class="card">
            <div class="card-header bg-primary text-white">
                <div>
                    <h5 class="mb-1">Bill Payments (BBPS)</h5>
                    <p class="mb-0">Select category and fetch billers/operators</p>
                </div>
            </div>

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold">Category</label>
                        <select id="ecardseva_bbps_category" class="form-select">
                            @php $cat = $category ?? 'electricity'; @endphp
                            @foreach(['electricity'=>'Electricity','water'=>'Water','gas'=>'Gas','broadband'=>'Broadband'] as $key=>$label)
                                <option value="{{ $key }}" {{ strtolower($cat)===$key ? 'selected':'' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold">Biller / Operator</label>
                        <select id="ecardseva_bbps_operator" class="form-select">
                            <option value="">Select Operator</option>
                            @if(isset($operators) && count($operators) > 0)
                                @foreach($operators as $op)
                                    <option value="{{ $op->operator_code }}">{{ $op->operator_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Consumer / Customer ID</label>
                        <input type="text" id="ecardseva_bbps_consumer" class="form-control" placeholder="Enter customer/account number">
                    </div>

                    <div class="col-12">
                        <button type="button" class="btn btn-primary" id="ecardseva_bbps_fetch">FETCH PLANS / CONFIRM</button>
                    </div>

                    <div class="col-12">
                        <div id="ecardseva_bbps_result" class="mt-2"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
(function(){
    const categorySelect = document.getElementById('ecardseva_bbps_category');
    const operatorSelect = document.getElementById('ecardseva_bbps_operator');
    const consumerInput = document.getElementById('ecardseva_bbps_consumer');
    const fetchBtn = document.getElementById('ecardseva_bbps_fetch');
    const resultEl = document.getElementById('ecardseva_bbps_result');

    fetchBtn?.addEventListener('click', function(){
        const category = categorySelect.value;
        const operator = operatorSelect.value;
        const consumer = (consumerInput.value||'').trim();

        if(!operator){ alert('Please select operator'); return; }
        if(!consumer){ alert('Please enter consumer ID'); return; }

        // Existing backend confirms via user.service.recharge.confirm.
        // We pass consumer as mobile and let confirm screen show details.
        const params = new URLSearchParams({
            mobile: consumer,
            operator: operator,
            circle: category,
            amount: '0',
            validity: 'NA',
            plan_desc: 'BBPS Bill Payment',
            service: 'bbps',
            category: category
        });
        window.location.href = "{{ route('ecard.recharge.confirm') }}?" + params.toString();
    });
})();
</script>
@endpush


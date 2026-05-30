<div class="row">
    <div class="col-12">
        <div class="page-header">
            <h4 class="page-title mb-0">FASTag Recharge</h4>
        </div>

        <div class="card">
            <div class="card-header bg-primary text-white">
                <div>
                    <h5 class="mb-1">FASTag Recharge</h5>
                    <p class="mb-0">Select bank, vehicle number, and amount</p>
                </div>
            </div>

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold">Vehicle Number</label>
                        <input type="text" id="ecardseva_fastag_vehicle" class="form-control" placeholder="e.g. MH12AB1234" style="text-transform: uppercase;">
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold">Bank/Operator</label>
                        <select id="ecardseva_fastag_operator" class="form-select">
                            <option value="">Select Bank</option>
                            @if(isset($operators) && count($operators) > 0)
                                @foreach($operators as $op)
                                    <option value="{{ $op->operator_code }}">{{ $op->operator_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Amount</label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" min="1" id="ecardseva_fastag_amount" class="form-control" placeholder="Enter amount">
                        </div>
                        <div class="mt-2 d-flex gap-2 flex-wrap">
                            @foreach([500,1000,2000,3000,5000] as $amt)
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="(function(){document.getElementById('ecardseva_fastag_amount').value='{{ $amt }}';})()">₹{{ $amt }}</button>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-12">
                        <button type="button" class="btn btn-primary w-100" id="ecardseva_fastag_proceed">PROCEED TO PAY</button>
                        <div class="text-center text-muted small mt-2">Proceeding will take you to payment confirmation.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function(){
    const proceedBtn = document.getElementById('ecardseva_fastag_proceed');
    const vehicleInput = document.getElementById('ecardseva_fastag_vehicle');
    const operatorSelect = document.getElementById('ecardseva_fastag_operator');
    const amountInput = document.getElementById('ecardseva_fastag_amount');

    proceedBtn?.addEventListener('click', function(){
        const vehicle = (vehicleInput.value||'').trim();
        const operator = operatorSelect.value;
        const amount = (amountInput.value||'').trim();

        if(!operator){ alert('Please select a Bank'); return; }
        if(!vehicle){ alert('Please enter Vehicle Number'); return; }
        if(!amount || Number(amount) <= 0){ alert('Please enter a valid amount'); return; }

        const params = new URLSearchParams({
            mobile: vehicle,
            operator: operator,
            circle: '',
            amount: amount,
            validity: 'NA',
            plan_desc: 'FASTag Recharge',
            service: 'fastag'
        });

        window.location.href = "{{ route('ecard.recharge.confirm') }}?" + params.toString();
    });
})();
</script>
@endpush


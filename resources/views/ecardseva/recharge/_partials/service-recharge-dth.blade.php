<div class="row">
    <div class="col-12">
        <div class="page-header">
            <h4 class="page-title mb-0">DTH Recharge</h4>
        </div>

        <div class="card">
            <div class="card-header bg-primary text-white">
                <div>
                    <h5 class="mb-1">DTH Recharge</h5>
                    <p class="mb-0">Enter subscriber ID and select operator to view plans</p>
                </div>
            </div>

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold">DTH Subscriber ID</label>
                        <input type="text" id="ecardseva_dth_subscriber" class="form-control" placeholder="Enter DTH number">
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold">Operator</label>
                        <select id="ecardseva_dth_operator" class="form-select">
                            <option value="">Select Operator</option>
                            @if(isset($operators) && count($operators) > 0)
                                @foreach($operators as $op)
                                    <option value="{{ $op->operator_code }}">{{ $op->operator_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-12">
                        <div id="ecardseva_dth_plans" class="mt-2">
                            <div class="text-center text-muted py-5">
                                Fill subscriber ID and choose operator.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
(function(){
    const subscriberInput = document.getElementById('ecardseva_dth_subscriber');
    const operatorSelect = document.getElementById('ecardseva_dth_operator');
    const plansEl = document.getElementById('ecardseva_dth_plans');

    function setLoading(){
        plansEl.innerHTML = '<div class="text-center py-5"><div class="spinner-border" role="status"></div><div class="mt-2 text-muted">Fetching plans...</div></div>';
    }

    async function post(url, data){
        return fetch(url, {
            method:'POST',
            headers:{
                'Content-Type':'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify(data)
        }).then(r=>r.json());
    }

    function renderPlans(plans){
        plansEl.innerHTML='';
        if(!plans || plans.length===0){
            plansEl.innerHTML='<div class="text-center text-muted py-5">No plans found.</div>';
            return;
        }
        const list = document.createElement('div');
        list.className='list-group';

        plans.forEach(plan => {
            const price = plan.price ?? plan.amount ?? plan.rs ?? '0';
            const validity = plan.validity ?? 'NA';
            const desc = plan.desc ?? plan.description ?? '';

            const item = document.createElement('div');
            item.className='list-group-item d-flex align-items-start gap-3 flex-wrap';
            item.innerHTML = `
                <div class="me-auto">
                    <div class="fw-bold fs-5">₹${price}</div>
                    <div class="text-muted small">Validity: ${validity}</div>
                    ${desc ? `<div class="text-muted" style="max-width:720px;">${desc}</div>` : ''}
                </div>
                <button class="btn btn-primary btn-sm btn-select-plan" data-plan='${JSON.stringify({price,validity,desc})}'>Select</button>
            `;
            list.appendChild(item);
        });

        plansEl.appendChild(list);

        plansEl.querySelectorAll('.btn-select-plan').forEach(btn => {
            btn.addEventListener('click', function(){
                const d = JSON.parse(this.getAttribute('data-plan'));
                const operatorCode = operatorSelect.value;
                const params = new URLSearchParams({
                    mobile: subscriberInput.value,
                    operator: operatorCode,
                    circle: '',
                    amount: d.price,
                    validity: d.validity,
                    plan_desc: d.desc,
                    service: 'dth'
                });
                window.location.href = "{{ route('ecard.recharge.confirm') }}?" + params.toString();
            });
        });
    }

    async function loadPlans(){
        const subscriber = (subscriberInput.value || '').trim();
        const operator = operatorSelect.value;
        if(!subscriber || !operator){
            plansEl.innerHTML='<div class="text-center text-muted py-5">Select operator and enter subscriber ID.</div>';
            return;
        }
        setLoading();

        const res = await post("{{ route('ecard.recharge.fetch-dth-plans') }}", {
            subscriber_id: subscriber,
            opcode: operator,
            _token: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        });

        let plans = res?.data ?? res;
        if(res?.plans) plans = res.plans;
        if(!Array.isArray(plans)) plans = [];
        renderPlans(plans);
    }

    subscriberInput?.addEventListener('input', loadPlans);
    operatorSelect?.addEventListener('change', loadPlans);
})();
</script>
@endpush


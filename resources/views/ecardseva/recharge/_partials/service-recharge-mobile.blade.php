<div class="row">
    <div class="col-12">
        <div class="page-header">
            <h4 class="page-title mb-0">Mobile Recharge</h4>
        </div>

        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="mb-1">Recharge Mobile</h5>
                        <p class="mb-0">Enter your mobile number to browse plans</p>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">Mobile Number</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text"><i data-feather="phone"></i></span>
                            <input type="tel" id="ecardseva_mobile_number" class="form-control" placeholder="Enter 10-digit number" maxlength="10" pattern="\\d{10}">
                        </div>
                        <div class="mt-2 small text-muted">Operator and plans will load automatically.</div>
                    </div>

                    <div class="col-12" id="ecardseva_operator_block" style="display:none;">
                        <div class="alert alert-light border" role="alert">
                            <div class="d-flex align-items-center gap-2">
                                <div id="ecardseva_operator_logo" style="width:40px;height:40px;display:none;" class="rounded-circle bg-white d-flex align-items-center justify-content-center overflow-hidden">
                                    <img src="" alt="" style="width:100%;height:100%;object-fit:contain;" />
                                </div>
                                <div>
                                    <div class="fw-bold" id="ecardseva_operator_name">Operator</div>
                                    <div class="text-muted small" id="ecardseva_circle_name">Circle</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12" id="ecardseva_plans_block">
                        <div class="text-center text-muted py-5" id="ecardseva_plans_placeholder">
                            Enter mobile number to view plans
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
    const mobileInput = document.getElementById('ecardseva_mobile_number');
    const operatorBlock = document.getElementById('ecardseva_operator_block');
    const plansBlock = document.getElementById('ecardseva_plans_block');
    const placeholder = document.getElementById('ecardseva_plans_placeholder');

    const operatorNameEl = document.getElementById('ecardseva_operator_name');
    const circleNameEl = document.getElementById('ecardseva_circle_name');
    const operatorLogoWrap = document.getElementById('ecardseva_operator_logo');
    const operatorLogoImg = operatorLogoWrap ? operatorLogoWrap.querySelector('img') : null;

    let currentActiveTab = null;
    let allPlansFlat = [];
    let currentPlans = {};

    function setPlaceholder(html){
        placeholder && (placeholder.innerHTML = html);
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

    function renderSimplePlansList(plans){
        plansBlock.innerHTML = '';

        if(!plans || plans.length === 0){
            plansBlock.innerHTML = '<div class="text-center text-muted py-5">No plans found.</div>';
            return;
        }

        const container = document.createElement('div');
        container.className = 'list-group';

        plans.forEach(plan => {
            const price = plan.price ?? plan.amount ?? plan.rs ?? '0';
            const validity = plan.validity ?? 'NA';
            const desc = plan.desc ?? plan.description ?? '';

            const item = document.createElement('div');
            item.className = 'list-group-item d-flex align-items-start gap-3 flex-wrap';
            item.innerHTML = `
                <div class="me-auto">
                    <div class="fw-bold fs-5">₹${price}</div>
                    <div class="text-muted small">Validity: ${validity}</div>
                    ${desc ? `<div class="text-muted" style="max-width:720px;">${desc}</div>` : ''}
                </div>
                <button class="btn btn-primary btn-sm btn-select-plan" data-plan='${JSON.stringify({price,validity,desc})}'>Select</button>
            `;

            container.appendChild(item);
        });

        plansBlock.appendChild(container);

        document.querySelectorAll('.btn-select-plan').forEach(btn => {
            btn.addEventListener('click', function(){
                const d = JSON.parse(this.getAttribute('data-plan'));
                const params = new URLSearchParams({
                    mobile: mobileInput.value,
                    operator: operatorNameEl?.innerText?.trim() || '',
                    circle: circleNameEl?.innerText?.trim() || '',
                    amount: d.price,
                    validity: d.validity,
                    plan_desc: d.desc
                });
                window.location.href = "{{ route('ecard.recharge.confirm') }}?" + params.toString();
            });
        });
    }

    async function fetchOperator(mobile){
        placeholder && (placeholder.innerHTML = '<div class="text-center py-5"><div class="spinner-border" role="status"></div><div class="mt-2 text-muted">Fetching operator...</div></div>');
        operatorBlock.style.display = 'none';

        const res = await post("{{ route('ecard.recharge.fetch-operator') }}", {mobile});

        const data = res?.data ?? res;
        const opName = data.company || data.operator_name || data.Operator || data.operator || '';
        const circleName = data.circle || data.circle_name || data.Circle || data.circle_name || '';
        const opcode = data.company_code || data.opcode || data.operator_code;
        const circleCode = data.circle_code || data.circlecode;
        const logoUrl = data.operator_logo || data.Logo || data.logo;

        if(opName){
            operatorNameEl.textContent = opName;
            circleNameEl.textContent = circleName;
            operatorBlock.style.display = 'block';
        }

        if(logoUrl && operatorLogoImg){
            operatorLogoWrap.style.display = 'block';
            operatorLogoImg.src = logoUrl;
        } else {
            if(operatorLogoWrap) operatorLogoWrap.style.display='none';
        }

        return {opcode, circleCode};
    }

    async function fetchPlans(mobile, opcode, circle){
        placeholder && (placeholder.innerHTML = '<div class="text-center py-5"><div class="spinner-border" role="status"></div><div class="mt-2 text-muted">Fetching plans...</div></div>');

        const res = await post("{{ route('ecard.recharge.fetch-plans') }}", {mobile, opcode, circle});
        let plansData = res?.data ?? res?.plans ?? res;

        if(res?.data?.plans) plansData = res.data.plans;
        const plans = Array.isArray(plansData) ? plansData : [];

        renderSimplePlansList(plans);
    }

    mobileInput?.addEventListener('input', async function(){
        const val = (this.value || '').replace(/\\D/g,'');
        this.value = val;

        if(val.length !== 10){
            operatorBlock.style.display='none';
            plansBlock.innerHTML = '<div class="text-center text-muted py-5" id="ecardseva_plans_placeholder">Enter mobile number to view plans</div>';
            return;
        }

        const {opcode, circleCode} = await fetchOperator(val);
        if(opcode && circleCode){
            await fetchPlans(val, opcode, circleCode);
        } else {
            plansBlock.innerHTML = '<div class="text-center text-danger py-5">Unable to fetch plans (operator codes missing).</div>';
        }
    });
})();
</script>
@endpush


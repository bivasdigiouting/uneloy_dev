<div class="row">
    <div class="col-12">
        <div class="page-header">
            <h4 class="page-title mb-0">Confirm Recharge</h4>
        </div>

        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Payment Confirmation</h5>
            </div>

            <div class="card-body">
                @php
                    $service = request('service', request('service', 'mobile'));
                    $mobile = request('mobile');
                    $operator = request('operator');
                    $circle = request('circle');
                    $amount = request('amount');
                    $validity = request('validity');
                    $planDesc = request('plan_desc') ?? request('desc');
                @endphp

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <div class="p-3 border rounded-3 bg-light">
                            <div class="text-muted small">Account / Number</div>
                            <div class="fw-bold fs-5">{{ $mobile }}</div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="p-3 border rounded-3 bg-light">
                            <div class="text-muted small">Operator / Category</div>
                            <div class="fw-bold fs-5">{{ $operator }} {{ $circle ? '- '.$circle : '' }}</div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="p-3 border rounded-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <div class="text-muted small">Validity</div>
                                    <div class="fw-semibold">{{ $validity ?? 'NA' }}</div>
                                </div>
                                <div class="text-end">
                                    <div class="text-muted small">Amount Payable</div>
                                    <div class="fw-bold fs-4 text-primary">₹{{ $amount }}</div>
                                </div>
                            </div>
                            @if($planDesc)
                                <div class="text-muted mt-2">{{ $planDesc }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-12">
                        <button type="button" class="btn btn-primary w-100" id="ecardseva_confirm_pay">Pay ₹{{ $amount }}</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script src="https://sdk.cashfree.com/js/v3/cashfree.js"></script>
<script>
$(function(){
    const cashfree = Cashfree({ mode: "{{ $cashfreeMode ?? 'sandbox' }}" });

    function showAlert(msg, type){
        Swal.fire({
            icon: type || 'success',
            title: msg
        });
    }

    $('#ecardseva_confirm_pay').on('click', function(){
        const amount = "{{ $amount }}";
        const mobile = "{{ $mobile }}";
        const operator = "{{ $operator }}";
        const circle = "{{ $circle }}";
        const planDesc = "{{ $planDesc }}";
        const service = "{{ $service }}";

        if(!amount || !mobile || !operator){
            showAlert('Missing required details', 'error');
            return;
        }

        $.ajax({
            url: "{{ route('ecard.recharge.create-order') }}",
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: { amount, mobile, operator, circle, plan_desc: planDesc, service },
            success: function(response){
                if(response.status === 'success' && response.payment_session_id){
                    cashfree.checkout({
                        paymentSessionId: response.payment_session_id,
                        redirectTarget: '_self'
                    });
                } else {
                    showAlert(response.message || 'Payment initiation failed', 'error');
                }
            },
            error: function(){
                showAlert('Failed to initiate payment', 'error');
            }
        });
    });
});
</script>
@endpush


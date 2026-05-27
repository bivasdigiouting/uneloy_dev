@extends('vendor.layout')

@section('title', 'Cashfree Checkout')

@section('content')
    <div class="p-6">
        <h2 class="text-xl font-bold text-slate-900 mb-4">Processing Cashfree Payment...</h2>
        <p class="text-sm text-slate-600">Redirecting to payment gateway. Please wait.</p>
    </div>

    {{-- Cashfree SDK checkout if you have a workable setup. --}}
    {{-- If not available, you can still manually redirect to a hosted URL returned by backend. --}}
    <script src="https://sdk.cashfree.com/js/v3/cashfree.js"></script>

    <script>
        (function () {
            const paymentSessionId = @json($payment_session_id ?? null);
            if (!paymentSessionId) {
                alert('Missing payment_session_id for Cashfree checkout');
                return;
            }

            // Environment mapping: use production in LIVE, sandbox otherwise
            const mode = @json(($environment ?? 'sandbox') === 'production' ? 'production' : 'sandbox');

            try {
                const cashfree = Cashfree({ mode: mode });

                cashfree.checkout({
                    paymentSessionId: paymentSessionId,
                    redirectTarget: '_self'
                });
            } catch (e) {
                console.error(e);
                alert('Cashfree checkout initialization failed');
            }
        })();
    </script>
@endsection


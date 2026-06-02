@extends('vendor.layout')

@section('title', 'PhonePe Checkout')

@section('content')
<div class="p-6">
    <h2 class="text-xl font-bold text-slate-900 mb-2">Redirecting to PhonePe...</h2>
    <p class="text-sm text-slate-600">Please wait. Do not refresh this page.</p>

    @if(isset($transaction_id))
        <div class="mt-4 text-xs text-slate-500">
            Transaction ID: {{ $transaction_id }}
        </div>
    @endif
    @if(isset($amount))
        <div class="text-xs text-slate-500">
            Amount: {{ $amount }}
        </div>
    @endif
</div>

<script>
(function () {
    const redirectUrl = @json($redirect_url ?? null);
    if (!redirectUrl) {
        alert('PhonePe initiate failed: missing redirectUrl');
        return;
    }
    // Redirect using full URL returned by PhonePe.
    window.location.href = redirectUrl;
})();
</script>
@endsection


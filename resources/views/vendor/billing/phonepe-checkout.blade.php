@extends('vendor.layout')

@section('title', 'PhonePe Checkout')

@section('content')
<div class="p-6">
    <h2 class="text-xl font-bold text-slate-900 mb-2">Processing PhonePe Payment...</h2>
    <p class="text-sm text-slate-600">Redirecting to PhonePe. Please wait.</p>
</div>

{{--
NOTE:
Full PhonePe checkout requires server-side checksum generation and initiate call.
If you already have working PhonePe flow elsewhere, replace this page to call it.
For now this page is a placeholder to prove the Pay Now click -> redirect pipeline.
--}}

<script>
    (function(){
        // Placeholder: no-op
        // You should integrate actual PhonePe redirect here.
        console.warn('PhonePe checkout page is placeholder - implement server-side PhonePe initiate + redirect.');
        setTimeout(function(){
            alert('PhonePe checkout not yet implemented. Contact admin or enable working gateway flow.');
        }, 500);
    })();
</script>

@endsection


@extends('user.layout')

@section('title', 'PhonePe Checkout')

@section('content')
<div class="p-6">
    <h2 class="text-xl font-bold text-slate-900 mb-2">Redirecting to PhonePe...</h2>
    <p class="text-sm text-slate-600">Please wait. Do not refresh.</p>

    @if(isset($redirectUrl) && $redirectUrl)
        <script>
            window.location.href = @json($redirectUrl);
        </script>
    @endif
</div>
@endsection


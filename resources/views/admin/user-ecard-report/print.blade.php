@extends('layouts.admin')
@section('title','Print E-Card')
@section('content')
<div class="content">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Print E-Card</h2>
    <div><button onclick="window.print()" class="btn btn-primary"><i class="ti ti-printer"></i> Print</button></div>
  </div>
  <div class="card"><div class="card-body">
    @if($record)
      <div class="row g-3">
        <div class="col-md-4"><strong>ID:</strong> {{ $record->id }}</div>
        <div class="col-md-4"><strong>Name:</strong> {{ trim(($record->first_name ?? '').' '.($record->middle_name ?? '').' '.($record->last_name ?? '')) }}</div>
        <div class="col-md-4"><strong>Email:</strong> {{ $record->email_id ?? $record->gmail_id }}</div>
        <div class="col-md-4"><strong>Mobile:</strong> {{ $record->mobile_no }}</div>
        <div class="col-md-4"><strong>State:</strong> {{ $record->state ?? '' }}</div>
        <div class="col-md-4"><strong>District:</strong> {{ $record->district ?? '' }}</div>
        <div class="col-md-4"><strong>City:</strong> {{ $record->city ?? '' }}</div>
        <div class="col-md-4"><strong>E-Card No:</strong> {{ $record->ecard_no ?? $record->ecard_number ?? '' }}</div>
        <div class="col-md-4"><strong>Status:</strong> {{ ($record->status ?? 1)==1? 'Active':'De-Active' }}</div>
        <div class="col-md-4"><strong>Created:</strong> {{ optional($record->created_at)->format('d-m-Y') }}</div>
        <div class="col-md-4"><strong>Expiry:</strong> {{ optional($record->expiry_date)->format('d-m-Y') }}</div>
        <div class="col-md-4"><strong>EEV No:</strong> {{ $record->eev_no ?? '' }}</div>
        <div class="col-md-4"><strong>Security No:</strong> {{ $record->security_number ?? '' }}</div>
      </div>
    @else
      <p class="text-danger mb-0">Record not found.</p>
    @endif
  </div></div>
</div>
@endsection
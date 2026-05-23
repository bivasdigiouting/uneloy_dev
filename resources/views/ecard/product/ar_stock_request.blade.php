@extends('ecard.ecard')

@section('title', 'A & R Product Stock Request')

@section('content')
<div class="container-fluid py-3">
  <h4 class="mb-3">A &amp; R Product Stock Request</h4>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="card shadow-sm">
    <div class="card-body">
      <form method="POST" action="{{ route('ecard.product.ar.stock.request.store') }}" novalidate>
        @csrf
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Member ID</label>
            <input type="text" name="member_id" class="form-control" value="{{ old('member_id') }}" placeholder="Enter Member ID" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Product Name</label>
            <input type="text" name="product_name" class="form-control" value="{{ old('product_name') }}" placeholder="Enter product name" required>
          </div>
          <div class="col-md-2">
            <label class="form-label">Quantity</label>
            <input type="number" step="0.01" min="0.01" name="quantity" class="form-control" value="{{ old('quantity') }}" placeholder="Qty" required>
          </div>
          <div class="col-md-2">
            <label class="form-label">Unit</label>
            <input type="text" name="unit" class="form-control" value="{{ old('unit') }}" placeholder="pcs / kg / box">
          </div>
          <div class="col-12">
            <label class="form-label">Remark</label>
            <input type="text" name="remark" class="form-control" value="{{ old('remark') }}" placeholder="Remark (optional)">
          </div>
        </div>
        <div class="mt-3">
          <button type="submit" class="btn btn-primary">Submit A &amp; R Request</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
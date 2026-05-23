@extends('layouts.admin')

@section('title', 'Add Redeem Value')

@section('content')

  <div class="content">
    <div class="page-header">
      <div class="row align-items-center">
        <div class="col">
          <h3 class="page-title">Add Redeem Value</h3>
          <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Redeem Modules</li>
            <li class="breadcrumb-item active">Add Redeem Value</li>
          </ul>
        </div>
      </div>
    </div>

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

    <div class="card">
      <div class="card-body">
        <form method="POST" action="{{ route('admin.redeem-values.update') }}">
          @csrf
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Total User Point</label>
                <input type="number" step="0.01" class="form-control" name="total_user_points" value="{{ old('total_user_points', optional($setting)->total_user_points) }}" required>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Redeem Amount</label>
                <input type="number" step="0.01" class="form-control" name="redeem_amount" value="{{ old('redeem_amount', optional($setting)->redeem_amount) }}" required>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Redeem Value</label>
                <input type="number" step="0.01" class="form-control" name="redeem_value" value="{{ old('redeem_value', optional($setting)->redeem_value) }}" required>
              </div>
            </div>
          </div>

          <div class="text-end">
            <button type="submit" class="btn btn-primary">Update</button>
          </div>
        </form>
      </div>
    </div>
  </div>

@endsection
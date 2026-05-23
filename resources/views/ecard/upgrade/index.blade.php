@extends('ecard.ecard')

@section('title', 'User Upgrade Id')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <h5 class="mb-0">User Upgrade Id</h5>
            <small class="text-muted">Upgrade a registered user to an upper level</small>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
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
            <form action="{{ route('ecard.upgrade.store') }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-4">
                    <label for="member_no" class="form-label">Member ID</label>
                    <input type="text" class="form-control" id="member_no" name="member_no" placeholder="Enter Member ID" value="{{ old('member_no') }}">
                </div>
                <div class="col-md-4">
                    <label for="to_level" class="form-label">Upgrade To Level</label>
                    <select id="to_level" name="to_level" class="form-select">
                        <option value="">Select level</option>
                        @php
                            $levelLabels = [
                                'customer' => 'Member',
                                'village_level' => 'e-Card Seva',
                                'panchayat_level' => 'G P M e-Card Seva',
                                'block_level' => 'Block - e-Card Seva',
                                'district_level' => 'District e-Card Seva',
                                'state_level' => 'State e-Card Seva',
                            ];
                        @endphp
                        @foreach($levels as $lvl)
                            <option value="{{ $lvl }}" @selected(old('to_level') === $lvl)>{{ $levelLabels[$lvl] ?? ucwords(str_replace('_',' ', $lvl)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="remark" class="form-label">Remark (optional)</label>
                    <input type="text" class="form-control" id="remark" name="remark" placeholder="Reason or note" value="{{ old('remark') }}">
                </div>
                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-arrow-up me-1"></i> Upgrade User
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="alert alert-info mt-3 mb-0">
        <i class="fa fa-info-circle"></i>
        Target level must be higher than the user's current level. Allowed sequence: Member → e-Card Seva → G P M e-Card Seva → Block - e-Card Seva → District e-Card Seva → State e-Card Seva.
    </div>
</div>
@endsection

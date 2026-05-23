@extends('layouts.public')

@section('content')
<main class="container my-4" style="min-height: 60vh;">
    <div class="row">
        <div class="col-12 mb-3">
            <div class="d-flex flex-wrap align-items-center justify-content-between">
                <div>
                    <h1 class="mb-1">{{ $supportSettings && $supportSettings->page_title ? $supportSettings->page_title : 'Help & Support' }}</h1>
                    <p class="text-muted mb-0">
                        {{ $supportSettings && $supportSettings->intro_text ? $supportSettings->intro_text : 'Reach our support team or browse helpline contacts by location.' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="col-12 mb-4">
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <div class="me-2"><i class="fa fa-envelope-o"></i></div>
                                <h6 class="mb-0">Email Support</h6>
                            </div>
                            <div class="text-muted small mb-2">{{ optional($supportSettings)->support_email ?: 'Not available' }}</div>
                            @if(optional($supportSettings)->support_email)
                                <a href="mailto:{{ $supportSettings->support_email }}" class="btn btn-outline-primary btn-sm w-100">Email Now</a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <div class="me-2"><i class="fa fa-phone"></i></div>
                                <h6 class="mb-0">Call Support</h6>
                            </div>
                            <div class="text-muted small mb-2">{{ optional($supportSettings)->support_phone ?: 'Not available' }}</div>
                            @if(optional($supportSettings)->support_phone)
                                <a href="tel:{{ $supportSettings->support_phone }}" class="btn btn-outline-primary btn-sm w-100">Call Now</a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <div class="me-2"><i class="fa fa-whatsapp"></i></div>
                                <h6 class="mb-0">WhatsApp</h6>
                            </div>
                            <div class="text-muted small mb-2">{{ optional($supportSettings)->support_whatsapp ?: 'Not available' }}</div>
                            @if(optional($supportSettings)->support_whatsapp)
                                <a href="https://wa.me/{{ preg_replace('/\\D+/', '', $supportSettings->support_whatsapp) }}" target="_blank" class="btn btn-outline-success btn-sm w-100">Chat on WhatsApp</a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <div class="me-2"><i class="fa fa-comments-o"></i></div>
                                <h6 class="mb-0">Live Chat</h6>
                            </div>
                            <div class="text-muted small mb-2">{{ optional($supportSettings)->live_chat_url ? 'Available' : 'Not available' }}</div>
                            @if(optional($supportSettings)->live_chat_url)
                                <a href="{{ $supportSettings->live_chat_url }}" target="_blank" class="btn btn-outline-primary btn-sm w-100">Start Chat</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($supportSettings && ($supportSettings->support_address || $supportSettings->working_hours))
            <div class="col-12 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <h6 class="mb-1">Address</h6>
                                <div class="text-muted">{{ $supportSettings->support_address ?: '-' }}</div>
                            </div>
                            <div class="col-md-4">
                                <h6 class="mb-1">Working Hours</h6>
                                <div class="text-muted">{{ $supportSettings->working_hours ?: '-' }}</div>
                            </div>
                        </div>
                        @if($supportSettings->additional_info)
                            <hr>
                            <div>{!! $supportSettings->additional_info !!}</div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
                        <div>
                            <h4 class="mb-0">Helpline Directory</h4>
                            <div class="text-muted small">Filter by state or search by name/number.</div>
                        </div>
                    </div>

                    <form method="get" class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label for="state_id" class="form-label">State</label>
                            <select id="state_id" name="state_id" class="form-select">
                                <option value="">All States</option>
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}" {{ request('state_id') == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="q" class="form-label">Search</label>
                            <input type="text" id="q" name="q" value="{{ request('q') }}" class="form-control" placeholder="Name or number">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">Filter</button>
                            <a href="{{ route('help-support.index') }}" class="btn btn-outline-secondary">Reset</a>
                        </div>
                    </form>

                    <div class="row">
                        @forelse($helplines as $helpline)
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title mb-1">{{ $helpline->name }}</h5>
                                        <p class="mb-2"><strong>Number:</strong> {{ $helpline->number }}</p>
                                        <p class="mb-0 text-muted">
                                            {{ optional($helpline->city)->name }}
                                            @if($helpline->city && $helpline->district) , @endif
                                            {{ optional($helpline->district)->name }}
                                            @if(($helpline->city || $helpline->district) && $helpline->state) , @endif
                                            {{ optional($helpline->state)->name }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info mb-0">No helpline records found.</div>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-3">
                        {{ $helplines->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

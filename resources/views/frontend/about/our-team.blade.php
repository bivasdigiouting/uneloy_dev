@extends('layouts.public')

@section('content')
<main class="container my-5" style="min-height: 50vh;">
    <div class="text-center mb-5 mt-4">
        <h1 class="display-6 fw-bold text-primary">Our Team</h1>
        <p class="text-muted">Meet the dedicated professionals leading our vision forward.</p>
    </div>

    <div class="row g-4">
        @forelse($teamMembers as $member)
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm text-center team-card">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            @if($member->image_url)
                                <img src="{{ $member->image_url }}" alt="{{ $member->name }}" class="rounded-circle img-thumbnail shadow-sm" style="width: 150px; height: 150px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto text-secondary shadow-sm" style="width: 150px; height: 150px; font-size: 3rem;">
                                    <i class="ti ti-user"></i>
                                </div>
                            @endif
                        </div>
                        <h5 class="card-title fw-bold mb-1">{{ $member->name }}</h5>
                        <h6 class="text-primary mb-3">{{ $member->designation }}</h6>
                        
                        <div class="d-flex justify-content-center gap-2 mb-3">
                            @if($member->facebook_link)
                                <a href="{{ $member->facebook_link }}" target="_blank" class="text-secondary hover-primary"><i class="ti ti-brand-facebook fs-4"></i></a>
                            @endif
                            @if($member->twitter_link)
                                <a href="{{ $member->twitter_link }}" target="_blank" class="text-secondary hover-primary"><i class="ti ti-brand-twitter fs-4"></i></a>
                            @endif
                            @if($member->linkedin_link)
                                <a href="{{ $member->linkedin_link }}" target="_blank" class="text-secondary hover-primary"><i class="ti ti-brand-linkedin fs-4"></i></a>
                            @endif
                            @if($member->instagram_link)
                                <a href="{{ $member->instagram_link }}" target="_blank" class="text-secondary hover-primary"><i class="ti ti-brand-instagram fs-4"></i></a>
                            @endif
                        </div>

                        <div class="text-muted small">
                            @if($member->email)
                                <div class="mb-1 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-mail me-2"></i> <a href="mailto:{{ $member->email }}" class="text-decoration-none text-muted">{{ $member->email }}</a>
                                </div>
                            @endif
                            @if($member->contact_no)
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="ti ti-phone me-2"></i> {{ $member->contact_no }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted fs-5">No team members available at the moment.</p>
            </div>
        @endforelse
    </div>
</main>
@endsection

@push('styles')
<style>
    .team-card { transition: transform 0.3s ease, box-shadow 0.3s ease; border-radius: 10px; }
    .team-card:hover { transform: translateY(-5px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; }
    .hover-primary { transition: color 0.2s ease; }
    .hover-primary:hover { color: #0d6efd !important; }
</style>
@endpush

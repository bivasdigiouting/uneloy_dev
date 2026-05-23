@extends('user.layouts.app')

@section('title', 'Advertisement - UOnly')

@push('styles')
<style>
    /* Match dashboard background */
    body { background-color: var(--bg-light); color: var(--text-dark); }

    /* Match dashboard navbar gradient - handled by global theme styles */
    /* .navbar.bg-dark {
        background: var(--primary-gradient) !important;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    } */

    .card { border: none; border-radius: 14px; box-shadow: 0 6px 20px rgba(13,110,253,.12); background-color: var(--card-bg); color: var(--text-dark); }
    .table { background: var(--card-bg); color: var(--text-dark); }
    .table-hover tbody tr:hover { color: var(--text-dark); background-color: rgba(0,0,0,0.05); }
    [data-theme="dark"] .table-hover tbody tr:hover { background-color: rgba(255,255,255,0.05); }
    .table > :not(caption) > * > * { background-color: transparent; color: inherit; }
    .page-header { margin-bottom: 1rem; }
    
    /* Modal styling for dark mode compatibility */
    .modal-content { background-color: var(--card-bg); color: var(--text-dark); }
    .form-control { background-color: var(--bg-light); color: var(--text-dark); border: 1px solid var(--muted-text); }
    .form-select { background-color: var(--bg-light); color: var(--text-dark); border: 1px solid var(--muted-text); }
</style>
@endpush

@section('content')
<div class="py-2">
    <div class="page-header d-flex align-items-center justify-content-between">
        <div>
            <h4 class="mb-1"><i class="fa-solid fa-bullhorn me-2"></i>Manage Advertisements</h4>
            <p class="text-muted mb-0">Create and manage your ad campaigns.</p>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newAdModal"><i class="fa-solid fa-plus me-1"></i>New Ad</button>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Budget</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ads as $ad)
                            <tr>
                                <td>{{ $ad['title'] }}</td>
                                <td>
                                    <span class="badge {{ $ad['status'] === 'Active' ? 'bg-success' : 'bg-secondary' }}">{{ $ad['status'] }}</span>
                                </td>
                                <td>₹ {{ number_format($ad['budget'], 0) }}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary">Edit</button>
                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No ads yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- New Ad Modal -->
    <div class="modal fade" id="newAdModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Advertisement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Title</label>
                                <input type="text" class="form-control" placeholder="Campaign title">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Budget (₹)</label>
                                <input type="number" class="form-control" placeholder="e.g., 5000">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Status</label>
                                <select class="form-select">
                                    <option>Active</option>
                                    <option>Paused</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" rows="3" placeholder="Ad details"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
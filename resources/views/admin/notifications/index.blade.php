@extends('layouts.admin')

@section('title', 'Notifications')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Notifications</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a></li>
                    <li class="breadcrumb-item">Notification Modules</li>
                    <li class="breadcrumb-item active" aria-current="page">List</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <a href="{{ route('admin.notifications.create') }}" class="btn btn-primary d-inline-flex align-items-center">
                    <i class="ti ti-plus me-1"></i>Add Notification
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Notifications</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Send To</th>
                            <th>Sent</th>
                            <th>Sent At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($notifications as $n)
                            <tr>
                                <td>{{ $n->id }}</td>
                                <td>{{ $n->title }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $n->send_to)) }}</td>
                                <td>
                                    @if($n->is_sent)
                                        <span class="badge bg-success-transparent">Sent</span>
                                    @else
                                        <span class="badge bg-warning-transparent">Draft</span>
                                    @endif
                                </td>
                                <td>{{ $n->sent_at ? $n->sent_at->format('Y-m-d H:i') : '-' }}</td>
                                <td class="d-flex gap-2">
                                    <a href="{{ route('admin.notifications.edit', $n) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="ti ti-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.notifications.destroy', $n) }}" method="POST" onsubmit="return confirm('Delete this notification?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="ti ti-trash"></i> Delete</button>
                                    </form>
                                    @if(!$n->is_sent)
                                    <form action="{{ route('admin.notifications.send', $n) }}" method="POST" onsubmit="return confirm('Send this notification now?')">
                                        @csrf
                                        <button class="btn btn-sm btn-primary"><i class="ti ti-send"></i> Send</button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No notifications found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div>
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
 </div>
@endsection
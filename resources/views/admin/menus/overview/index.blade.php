@extends('layouts.admin')

@section('title', 'Menu Overview')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">{{ $menu->title }} - Overview</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        Website Modules
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.menus.index') }}">Main Menu</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Overview</li>
                </ol>
            </nav>
        </div>
        <div class="head-icons ms-2">
            <a href="{{ route('admin.menus.edit', $menu->id) }}" class="btn btn-primary">
                <i class="ti ti-edit me-1"></i>Edit
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title mb-0">Active Sub Menus</h4>
                        </div>
                        <div class="col-auto">
                            <span class="badge bg-secondary">{{ $children->count() }} items</span>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if($children->isEmpty())
                        <div class="alert alert-info">
                            No active submenu items found for <b>{{ $menu->title }}</b>.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Title</th>
                                        <th>URL/Route</th>
                                        <th>Status</th>
                                        <th>Open in New Tab</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($children as $i => $child)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ $child->title }}</td>
                                            <td>
                                                @if($child->route_name)
                                                    <code>{{ $child->route_name }}</code>
                                                @else
                                                    <span class="text-muted">{{ $child->url ?: '-' }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($child->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($child->open_in_new_tab)
                                                    <span class="badge bg-primary">Yes</span>
                                                @else
                                                    <span class="badge bg-light text-dark">No</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.menus.edit', $child->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


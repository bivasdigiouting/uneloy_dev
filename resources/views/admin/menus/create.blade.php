@extends('layouts.admin')

@section('title', 'Add Menu')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Create New Menu</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.menus.index') }}">Menu Manager</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Create</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
            <div class="me-2 mb-2">
                <a href="{{ route('admin.menus.index') }}" class="btn btn-light d-inline-flex align-items-center">
                    <i class="ti ti-arrow-left me-1"></i>Back to Menu Manager
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title mb-0">Add New Menu Item</h4>
                        </div>
                    </div>
                </div>
                <form action="{{ route('admin.menus.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-8">
                                <!-- Menu Title -->
                                <div class="form-group mb-3">
                                    <label for="title">Menu Title <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('title') is-invalid @enderror" 
                                           id="title" 
                                           name="title" 
                                           value="{{ old('title') }}" 
                                           placeholder="Enter menu title"
                                           required>
                                    @error('title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Menu Type -->
                                <div class="form-group mb-3">
                                    <label for="menu_type">Menu Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('menu_type') is-invalid @enderror" 
                                            id="menu_type" 
                                            name="menu_type" 
                                            required>
                                        <option value="">Select Menu Type</option>
                                        <option value="primary" {{ old('menu_type', request('type')) == 'primary' ? 'selected' : '' }}>
                                            Primary Menu
                                        </option>
                                        <option value="footer" {{ old('menu_type', request('type')) == 'footer' ? 'selected' : '' }}>
                                            Footer Menu
                                        </option>
                                    </select>
                                    @error('menu_type')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Parent Menu -->
                                <div class="form-group mb-3">
                                    <label for="parent_id">Parent Menu</label>
                                    <select class="form-select @error('parent_id') is-invalid @enderror" 
                                            id="parent_id" 
                                            name="parent_id">
                                        <option value="">No Parent (Top Level)</option>
                                        <optgroup label="Primary Menus" id="primary-parents">
                                            @foreach($parentMenus['primary'] as $menu)
                                                <option value="{{ $menu->id }}" {{ old('parent_id') == $menu->id ? 'selected' : '' }}>
                                                    {{ $menu->title }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                        <optgroup label="Footer Menus" id="footer-parents">
                                            @foreach($parentMenus['footer'] as $menu)
                                                <option value="{{ $menu->id }}" {{ old('parent_id') == $menu->id ? 'selected' : '' }}>
                                                    {{ $menu->title }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    </select>
                                    @error('parent_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <small class="form-text text-muted">Select a parent menu to create a sub-menu item</small>
                                </div>

                                <!-- URL Configuration -->
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">URL Configuration</h5>
                                    </div>
                                    <div class="card-body">
                                        <!-- URL Type Selection -->
                                        <div class="form-group mb-3">
                                            <label class="form-label">URL Type <span class="text-danger">*</span></label>
                                            <div class="form-check-container mt-2">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" 
                                                               type="radio" 
                                                               name="url_type_selection" 
                                                               id="url_type_custom" 
                                                               value="custom" 
                                                               {{ old('url_type_selection', 'custom') == 'custom' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="url_type_custom">
                                                        Custom URL
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" 
                                                               type="radio" 
                                                               name="url_type_selection" 
                                                               id="url_type_route" 
                                                               value="route" 
                                                               {{ old('url_type_selection') == 'route' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="url_type_route">
                                                        Laravel Route
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Custom URL -->
                                        <div class="form-group mb-3" id="custom-url-group">
                                            <label for="url">Custom URL</label>
                                            <input type="url" 
                                                   class="form-control @error('url') is-invalid @enderror" 
                                                   id="url" 
                                                   name="url" 
                                                   value="{{ old('url') }}" 
                                                   placeholder="https://example.com or /page-url">
                                            @error('url')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <small class="form-text text-muted">Enter full URL or relative path</small>
                                        </div>

                                        <!-- Route Name -->
                                        <div class="form-group mb-3" id="route-name-group" style="display: none;">
                                            <label for="route_name">Route Name</label>
                                            <input type="text" 
                                                   class="form-control @error('route_name') is-invalid @enderror" 
                                                   id="route_name" 
                                                   name="route_name" 
                                                   value="{{ old('route_name') }}" 
                                                   placeholder="home, about, contact">
                                            @error('route_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <small class="form-text text-muted">Enter Laravel route name (e.g., home, about)</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="form-group mb-3">
                                    <label for="description">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" 
                                              name="description" 
                                              rows="3" 
                                              placeholder="Enter menu description (optional)">{{ old('description') }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-md-4">
                                <!-- Menu Settings -->
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Menu Settings</h5>
                                    </div>
                                    <div class="card-body">
                                        <!-- Status -->
                                        <div class="form-group mb-3">
                                            <label>Status</label>
                                            <div class="form-check form-switch mt-2">
                                                <input class="form-check-input" 
                                                       type="checkbox" 
                                                       id="is_active" 
                                                       name="is_active" 
                                                       value="1" 
                                                       {{ old('is_active', '1') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_active">
                                                    Active
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Open in New Tab -->
                                        <div class="form-group mb-3">
                                            <label>Link Behavior</label>
                                            <div class="form-check form-switch mt-2">
                                                <input class="form-check-input" 
                                                       type="checkbox" 
                                                       id="open_in_new_tab" 
                                                       name="open_in_new_tab" 
                                                       value="1" 
                                                       {{ old('open_in_new_tab') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="open_in_new_tab">
                                                    Open in New Tab
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Sort Order -->
                                        <div class="form-group mb-3">
                                            <label for="sort_order">Sort Order</label>
                                            <input type="number" 
                                                   class="form-control @error('sort_order') is-invalid @enderror" 
                                                   id="sort_order" 
                                                   name="sort_order" 
                                                   value="{{ old('sort_order', 0) }}" 
                                                   min="0" 
                                                   placeholder="0">
                                            @error('sort_order')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <small class="form-text text-muted">Lower numbers appear first</small>
                                        </div>

                                        <!-- Icon -->
                                        <div class="form-group mb-3">
                                            <label for="icon">Icon Class</label>
                                            <input type="text" 
                                                   class="form-control @error('icon') is-invalid @enderror" 
                                                   id="icon" 
                                                   name="icon" 
                                                   value="{{ old('icon') }}" 
                                                   placeholder="ti ti-home, fas fa-home">
                                            @error('icon')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <small class="form-text text-muted">CSS icon class (optional)</small>
                                        </div>

                                        <!-- CSS Class -->
                                        <div class="form-group mb-3">
                                            <label for="css_class">CSS Class</label>
                                            <input type="text" 
                                                   class="form-control @error('css_class') is-invalid @enderror" 
                                                   id="css_class" 
                                                   name="css_class" 
                                                   value="{{ old('css_class') }}" 
                                                   placeholder="custom-class">
                                            @error('css_class')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <small class="form-text text-muted">Additional CSS classes (optional)</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary">
                                <i class="ti ti-arrow-left me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy me-1"></i>Create Menu
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Handle URL type switching
    $('input[name="url_type_selection"]').change(function() {
        const selectedType = $(this).val();
        
        if (selectedType === 'custom') {
            $('#custom-url-group').show();
            $('#route-name-group').hide();
            $('#url').prop('required', true);
            $('#route_name').prop('required', false);
        } else if (selectedType === 'route') {
            $('#custom-url-group').hide();
            $('#route-name-group').show();
            $('#url').prop('required', false);
            $('#route_name').prop('required', true);
        }
    });

    // Handle menu type change to filter parent options
    $('#menu_type').change(function() {
        const menuType = $(this).val();
        const parentSelect = $('#parent_id');
        
        // Reset parent selection
        parentSelect.val('');
        
        // Show/hide appropriate parent options
        if (menuType === 'primary') {
            $('#primary-parents').show();
            $('#footer-parents').hide();
        } else if (menuType === 'footer') {
            $('#primary-parents').hide();
            $('#footer-parents').show();
        } else {
            $('#primary-parents').show();
            $('#footer-parents').show();
        }
    });

    // Initialize based on current selection
    $('#menu_type').trigger('change');
    $('input[name="url_type_selection"]:checked').trigger('change');
});
</script>
@endpush
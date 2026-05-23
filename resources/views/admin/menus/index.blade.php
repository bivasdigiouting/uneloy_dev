@extends('layouts.admin')

@section('title', 'Menu Manager')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Menu Management</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        Website Modules
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Main Menu</li>
                </ol>
            </nav>
        </div>
        <div class="head-icons ms-2">
            <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Refresh" onclick="window.location.reload();">
                <i class="ti ti-refresh-dot"></i>
            </a>
        </div>
    </div>
    <!-- /Breadcrumb -->

    <!-- Menu Type Tabs -->
    <div class="row mb-3">
        <div class="col-12">
            <ul class="nav nav-tabs" id="menuTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab" aria-controls="all" aria-selected="true">
                        <i class="ti ti-list me-1"></i> All Menus
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="primary-tab" data-bs-toggle="tab" data-bs-target="#primary" type="button" role="tab" aria-controls="primary" aria-selected="false">
                        <i class="ti ti-menu-2 me-1"></i> Primary Menu
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="footer-tab" data-bs-toggle="tab" data-bs-target="#footer" type="button" role="tab" aria-controls="footer" aria-selected="false">
                        <i class="ti ti-layout-bottombar me-1"></i> Footer Menu
                    </button>
                </li>
            </ul>
        </div>
    </div>

    <!-- Tab Content -->
    <div class="tab-content" id="menuTabContent">
        <!-- All Menus Tab -->
        <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h4 class="card-title mb-0">All Menus</h4>
                                </div>
                                <div class="col-auto">
                                    <a href="{{ route('admin.menus.create') }}" class="btn btn-primary">
                                        <i class="ti ti-plus me-1"></i> Add New Menu
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="menusTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Title</th>
                                            <th>Type</th>
                                            <th>Parent</th>
                                            <th>Order</th>
                                            <th>Status</th>
                                            <th>Created</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data will be loaded via AJAX -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Primary Menu Tab -->
        <div class="tab-pane fade" id="primary" role="tabpanel" aria-labelledby="primary-tab">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h4 class="card-title mb-0">Primary Menu Structure</h4>
                                    <p class="text-muted mb-0">Drag and drop to reorder menu items</p>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-success me-2" onclick="saveMenuOrder('primary')">
                                        <i class="ti ti-device-floppy me-1"></i> Save Order
                                    </button>
                                    <a href="{{ route('admin.menus.create') }}?type=primary" class="btn btn-primary">
                                        <i class="ti ti-plus me-1"></i> Add Primary Menu
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="primaryMenuContainer" class="menu-container">
                                <div class="text-center py-4">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="mt-2">Loading menu structure...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Menu Tab -->
        <div class="tab-pane fade" id="footer" role="tabpanel" aria-labelledby="footer-tab">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h4 class="card-title mb-0">Footer Menu Structure</h4>
                                    <p class="text-muted mb-0">Drag and drop to reorder menu items</p>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-success me-2" onclick="saveMenuOrder('footer')">
                                        <i class="ti ti-device-floppy me-1"></i> Save Order
                                    </button>
                                    <a href="{{ route('admin.menus.create') }}?type=footer" class="btn btn-primary">
                                        <i class="ti ti-plus me-1"></i> Add Footer Menu
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="footerMenuContainer" class="menu-container">
                                <div class="text-center py-4">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="mt-2">Loading menu structure...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteMenuModal" tabindex="-1" aria-labelledby="deleteMenuModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteMenuModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this menu item? This action will also delete all its sub-menus and cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/responsive.bootstrap5.min.css') }}">
<style>
.menu-container {
    min-height: 300px;
}

.menu-item {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    margin-bottom: 10px;
    padding: 15px;
    cursor: move;
    transition: all 0.3s ease;
    position: relative;
}

.menu-item:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    border-color: #007bff;
}

.menu-item.dragging {
    opacity: 0.5;
    transform: rotate(5deg);
}

.menu-item .menu-header {
    display: flex;
    align-items: center;
    justify-content: between;
}

.menu-item .menu-title {
    font-weight: 600;
    color: #495057;
    margin-bottom: 5px;
}

.menu-item .menu-url {
    font-size: 0.875rem;
    color: #6c757d;
    margin-bottom: 0;
}

.menu-item .menu-actions {
    display: flex;
    gap: 5px;
}

.menu-item .drag-handle {
    cursor: grab;
    color: #6c757d;
    margin-right: 10px;
    font-size: 1.2rem;
}

.menu-item .drag-handle:active {
    cursor: grabbing;
}

.menu-children {
    margin-left: 30px;
    margin-top: 15px;
    padding-left: 20px;
    border-left: 2px solid #e9ecef;
}

.menu-children .menu-item {
    background: #f8f9fa;
    border-color: #dee2e6;
}

.sortable-ghost {
    opacity: 0.4;
}

.sortable-chosen {
    box-shadow: 0 0 0 2px #007bff;
}

.menu-type-badge {
    position: absolute;
    top: 10px;
    right: 10px;
}

.status-badge {
    position: absolute;
    top: 10px;
    right: 80px;
}
</style>
@endpush

@push('scripts')
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/js/responsive.bootstrap5.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#menusTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("admin.menus.index") }}',
            type: 'GET'
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'title', name: 'title' },
            { data: 'menu_type_badge', name: 'menu_type', orderable: false },
            { data: 'parent_name', name: 'parent.title', orderable: false },
            { data: 'sort_order', name: 'sort_order' },
            { data: 'is_active_badge', name: 'is_active', orderable: false },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        responsive: true,
        order: [[4, 'asc']],
        pageLength: 25,
        language: {
            processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
            emptyTable: "No menu items found",
            zeroRecords: "No matching menu items found"
        }
    });

    // Load hierarchical menus when tabs are clicked
    $('#primary-tab').on('click', function() {
        loadHierarchicalMenu('primary');
    });

    $('#footer-tab').on('click', function() {
        loadHierarchicalMenu('footer');
    });

    // Load primary menu by default
    loadHierarchicalMenu('primary');
});

// Load hierarchical menu structure
function loadHierarchicalMenu(type) {
    const container = $(`#${type}MenuContainer`);
    
    container.html(`
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading menu structure...</p>
        </div>
    `);

    $.ajax({
        url: '{{ route("admin.menus.hierarchical", ":type") }}'.replace(':type', type),
        type: 'GET',
        success: function(response) {
            if (response.success) {
                renderMenuStructure(container, response.data, type);
                initializeSortable(type);
            } else {
                container.html(`
                    <div class="alert alert-danger">
                        <i class="ti ti-alert-circle me-2"></i>
                        Failed to load menu structure: ${response.message}
                    </div>
                `);
            }
        },
        error: function(xhr) {
            container.html(`
                <div class="alert alert-danger">
                    <i class="ti ti-alert-circle me-2"></i>
                    Error loading menu structure. Please try again.
                </div>
            `);
        }
    });
}

// Render menu structure
function renderMenuStructure(container, menus, type) {
    if (menus.length === 0) {
        container.html(`
            <div class="text-center py-5">
                <i class="ti ti-menu-2 text-muted" style="font-size: 3rem;"></i>
                <h5 class="mt-3 text-muted">No ${type} menu items found</h5>
                <p class="text-muted">Create your first ${type} menu item to get started.</p>
                <a href="{{ route('admin.menus.create') }}?type=${type}" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i> Add ${type.charAt(0).toUpperCase() + type.slice(1)} Menu
                </a>
            </div>
        `);
        return;
    }

    let html = `<div class="sortable-menu" data-type="${type}">`;
    
    menus.forEach(menu => {
        html += renderMenuItem(menu);
    });
    
    html += '</div>';
    container.html(html);
}

// Render individual menu item
function renderMenuItem(menu) {
    const statusBadge = menu.is_active ? 
        '<span class="badge bg-success status-badge">Active</span>' : 
        '<span class="badge bg-danger status-badge">Inactive</span>';
    
    const typeBadge = menu.menu_type === 'primary' ? 
        '<span class="badge bg-primary menu-type-badge">Primary</span>' : 
        '<span class="badge bg-info menu-type-badge">Footer</span>';

    let html = `
        <div class="menu-item" data-id="${menu.id}">
            ${statusBadge}
            ${typeBadge}
            <div class="menu-header">
                <div class="d-flex align-items-center flex-grow-1">
                    <i class="ti ti-grip-vertical drag-handle"></i>
                    <div class="flex-grow-1">
                        <div class="menu-title">${menu.title}</div>
                        <div class="menu-url">${menu.full_url || menu.url || '#'}</div>
                    </div>
                </div>
                <div class="menu-actions">
                    <a href="/admin/menus/${menu.id}/edit" class="btn btn-sm btn-primary" title="Edit">
                        <i class="ti ti-edit"></i>
                    </a>
                    <button type="button" class="btn btn-sm btn-${menu.is_active ? 'warning' : 'success'}" 
                            onclick="toggleMenuStatus(${menu.id})" 
                            title="${menu.is_active ? 'Deactivate' : 'Activate'}">
                        <i class="ti ti-${menu.is_active ? 'eye-off' : 'eye'}"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-info" 
                            onclick="duplicateMenu(${menu.id})" title="Duplicate">
                        <i class="ti ti-copy"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-danger" 
                            onclick="deleteMenu(${menu.id})" title="Delete">
                        <i class="ti ti-trash"></i>
                    </button>
                </div>
            </div>
    `;

    if (menu.active_children && menu.active_children.length > 0) {
        html += '<div class="menu-children sortable-submenu">';
        menu.active_children.forEach(child => {
            html += renderMenuItem(child);
        });
        html += '</div>';
    }

    html += '</div>';
    return html;
}

// Initialize sortable functionality
function initializeSortable(type) {
    const container = document.querySelector(`#${type}MenuContainer .sortable-menu`);
    if (!container) return;

    new Sortable(container, {
        group: `${type}-menu`,
        animation: 150,
        handle: '.drag-handle',
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        dragClass: 'dragging',
        onStart: function(evt) {
            evt.item.classList.add('dragging');
        },
        onEnd: function(evt) {
            evt.item.classList.remove('dragging');
        }
    });

    // Initialize sortable for submenus
    const submenus = container.querySelectorAll('.sortable-submenu');
    submenus.forEach(submenu => {
        new Sortable(submenu, {
            group: `${type}-submenu`,
            animation: 150,
            handle: '.drag-handle',
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass: 'dragging'
        });
    });
}

// Save menu order
function saveMenuOrder(type) {
    const container = document.querySelector(`#${type}MenuContainer .sortable-menu`);
    if (!container) return;

    const menuOrder = [];
    const menuItems = container.children;

    for (let i = 0; i < menuItems.length; i++) {
        const item = menuItems[i];
        const menuData = {
            id: parseInt(item.dataset.id),
            parent_id: null,
            children: []
        };

        // Check for children
        const childrenContainer = item.querySelector('.sortable-submenu');
        if (childrenContainer) {
            const children = childrenContainer.children;
            for (let j = 0; j < children.length; j++) {
                menuData.children.push({
                    id: parseInt(children[j].dataset.id)
                });
            }
        }

        menuOrder.push(menuData);
    }

    // Send AJAX request to save order
    $.ajax({
        url: '{{ route("admin.menus.update-order") }}',
        type: 'POST',
        data: {
            menu_order: menuOrder,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                toastr.success(response.message);
                // Reload the menu structure
                loadHierarchicalMenu(type);
                // Reload DataTable
                $('#menusTable').DataTable().ajax.reload();
            } else {
                toastr.error(response.message);
            }
        },
        error: function(xhr) {
            toastr.error('Failed to save menu order. Please try again.');
        }
    });
}

// Delete menu
function deleteMenu(id) {
    $('#deleteMenuModal').modal('show');
    
    $('#confirmDeleteBtn').off('click').on('click', function() {
        $.ajax({
            url: `/admin/menus/${id}`,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#deleteMenuModal').modal('hide');
                if (response.success) {
                    toastr.success(response.message);
                    $('#menusTable').DataTable().ajax.reload();
                    // Reload hierarchical menus
                    loadHierarchicalMenu('primary');
                    loadHierarchicalMenu('footer');
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                $('#deleteMenuModal').modal('hide');
                toastr.error('Failed to delete menu. Please try again.');
            }
        });
    });
}

// Toggle menu status
function toggleMenuStatus(id) {
    $.ajax({
        url: `/admin/menus/${id}/toggle-status`,
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                toastr.success(response.message);
                $('#menusTable').DataTable().ajax.reload();
                // Reload hierarchical menus
                loadHierarchicalMenu('primary');
                loadHierarchicalMenu('footer');
            } else {
                toastr.error(response.message);
            }
        },
        error: function(xhr) {
            toastr.error('Failed to update menu status. Please try again.');
        }
    });
}

// Duplicate menu
function duplicateMenu(id) {
    $.ajax({
        url: `/admin/menus/${id}/duplicate`,
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                toastr.success(response.message);
                $('#menusTable').DataTable().ajax.reload();
                // Reload hierarchical menus
                loadHierarchicalMenu('primary');
                loadHierarchicalMenu('footer');
            } else {
                toastr.error(response.message);
            }
        },
        error: function(xhr) {
            toastr.error('Failed to duplicate menu. Please try again.');
        }
    });
}
</script>
@endpush
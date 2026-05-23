@extends('layouts.admin')

@section('title','User E-Card Permissions')

@section('content')
<div class="content container-fluid">
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">Permissions: {{ $user->full_name }} ({{ $user->user_id }})</h3>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="post" action="{{ route('admin.ecard-permissions.user.save', $user->id) }}">
                @csrf
                <div class="d-flex align-items-center justify-content-end mb-2">
                    <label class="form-check form-check-inline mb-0">
                        <input type="checkbox" class="form-check-input" id="global-select-all">
                        <span class="ms-2">Select All (All Modules)</span>
                    </label>
                </div>

                @foreach($modules as $parent)
                    <div class="mb-3 module-block" data-module-id="{{ $parent->id }}">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h5 class="mb-0">{{ $parent->title }}</h5>
                            <label class="form-check form-check-inline mb-0">
                                <input type="checkbox" class="form-check-input module-select-all">
                                <span class="ms-2">Select All (Module)</span>
                            </label>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm align-middle">
                                <thead>
                                    <tr>
                                        <th>Submodule</th>
                                        <th class="text-center">All</th>
                                        <th class="text-center">View</th>
                                        <th class="text-center">Create</th>
                                        <th class="text-center">Update</th>
                                        <th class="text-center">Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($parent->children as $child)
                                        @php $p = $perms[$child->id] ?? null; @endphp
                                        <tr>
                                            <input type="hidden" name="permissions[{{ $child->id }}][present]" value="1">
                                            <td>{{ $child->title }} <span class="text-muted">{{ $child->route_name }}</span></td>
                                            <td class="text-center">
                                                <input type="checkbox" class="form-check-input submodule-select-all">
                                            </td>
                                            <td class="text-center"><input type="checkbox" class="form-check-input perm-checkbox" name="permissions[{{ $child->id }}][view]" {{ ($p && $p->can_view)?'checked':'' }}></td>
                                            <td class="text-center"><input type="checkbox" class="form-check-input perm-checkbox" name="permissions[{{ $child->id }}][create]" {{ ($p && $p->can_create)?'checked':'' }}></td>
                                            <td class="text-center"><input type="checkbox" class="form-check-input perm-checkbox" name="permissions[{{ $child->id }}][update]" {{ ($p && $p->can_update)?'checked':'' }}></td>
                                            <td class="text-center"><input type="checkbox" class="form-check-input perm-checkbox" name="permissions[{{ $child->id }}][delete]" {{ ($p && $p->can_delete)?'checked':'' }}></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
                <div class="mt-3">
                    <button class="btn btn-success">Save Permissions</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    function updateRowSelectAll(row) {
        const permBoxes = row.querySelectorAll('.perm-checkbox');
        const allChecked = Array.from(permBoxes).every(cb => cb.checked);
        const allToggle = row.querySelector('.submodule-select-all');
        if (allToggle) allToggle.checked = allChecked;
    }

    function updateModuleSelectAll(block) {
        const permBoxes = block.querySelectorAll('.perm-checkbox');
        const allChecked = Array.from(permBoxes).length > 0 && Array.from(permBoxes).every(cb => cb.checked);
        const moduleToggle = block.querySelector('.module-select-all');
        if (moduleToggle) moduleToggle.checked = allChecked;
    }

    function updateGlobalSelectAll() {
        const allPermBoxes = document.querySelectorAll('.perm-checkbox');
        const globalToggle = document.getElementById('global-select-all');
        if (!globalToggle) return;
        const allChecked = allPermBoxes.length > 0 && Array.from(allPermBoxes).every(cb => cb.checked);
        globalToggle.checked = allChecked;
    }

    // Wire row-level select all
    document.querySelectorAll('.module-block tbody tr').forEach(function(row) {
        const rowToggle = row.querySelector('.submodule-select-all');
        if (rowToggle) {
            rowToggle.addEventListener('change', function() {
                const permBoxes = row.querySelectorAll('.perm-checkbox');
                permBoxes.forEach(cb => { cb.checked = rowToggle.checked; });
                const moduleBlock = row.closest('.module-block');
                if (moduleBlock) updateModuleSelectAll(moduleBlock);
                updateGlobalSelectAll();
            });
        }
        // Individual checkbox changes update row/module/global toggles
        row.querySelectorAll('.perm-checkbox').forEach(function(cb) {
            cb.addEventListener('change', function() {
                updateRowSelectAll(row);
                const moduleBlock = row.closest('.module-block');
                if (moduleBlock) updateModuleSelectAll(moduleBlock);
                updateGlobalSelectAll();
            });
        });
        // Initialize row toggle state
        updateRowSelectAll(row);
    });

    // Wire module-level select all
    document.querySelectorAll('.module-block').forEach(function(block) {
        const moduleToggle = block.querySelector('.module-select-all');
        if (moduleToggle) {
            moduleToggle.addEventListener('change', function() {
                const rows = block.querySelectorAll('tbody tr');
                rows.forEach(function(row) {
                    const permBoxes = row.querySelectorAll('.perm-checkbox');
                    permBoxes.forEach(cb => { cb.checked = moduleToggle.checked; });
                    const rowToggle = row.querySelector('.submodule-select-all');
                    if (rowToggle) rowToggle.checked = moduleToggle.checked;
                });
                updateGlobalSelectAll();
            });
        }
        // Initialize module toggle state
        updateModuleSelectAll(block);
    });

    // Global select all
    const globalToggle = document.getElementById('global-select-all');
    if (globalToggle) {
        globalToggle.addEventListener('change', function() {
            document.querySelectorAll('.module-block').forEach(function(block) {
                const rows = block.querySelectorAll('tbody tr');
                rows.forEach(function(row) {
                    const permBoxes = row.querySelectorAll('.perm-checkbox');
                    permBoxes.forEach(cb => { cb.checked = globalToggle.checked; });
                    const rowToggle = row.querySelector('.submodule-select-all');
                    if (rowToggle) rowToggle.checked = globalToggle.checked;
                });
                updateModuleSelectAll(block);
            });
        });
    }

    // Initialize global toggle
    updateGlobalSelectAll();

    // SweetAlert feedback after save
    @if(session('success'))
        if (window.Swal) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
                timer: 1800,
                showConfirmButton: false
            });
        } else {
            alert('{{ session('success') }}');
        }
    @endif
    @if(session('error'))
        if (window.Swal) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}'
            });
        } else {
            alert('{{ session('error') }}');
        }
    @endif
});
</script>
@endsection
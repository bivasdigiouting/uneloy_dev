@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h3 class="page-title">Stock Transfer</h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Stock Management</li>
                <li class="breadcrumb-item active">Stock Transfer</li>
            </ul>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Create Stock Transfer</h5>
            </div>
            <div class="card-body">
                <form id="stock-transfer-form">
                    @csrf
                    <div class="mb-3">
                        <label for="product_category_id" class="form-label">Product Category</label>
                        <select id="product_category_id" name="product_category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="product_id" class="form-label">Product</label>
                        <select id="product_id" name="product_id" class="form-select" required>
                            <option value="">Select Product</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" id="quantity" name="quantity" class="form-control" min="1" step="1" required />
                    </div>
                    <div class="mb-3">
                        <label for="remarks" class="form-label">Remarks</label>
                        <textarea id="remarks" name="remarks" class="form-control" rows="2" placeholder="Optional"></textarea>
                    </div>

                    <div class="border rounded p-3 mb-3">
                        <h6 class="mb-3">From Level</h6>
                        <div class="mb-3">
                            <label for="from_level_type" class="form-label">From Level Type</label>
                            <select id="from_level_type" name="from_level_type" class="form-select" required>
                                <option value="">Select Level</option>
                                <option value="state">State</option>
                                <option value="district">District</option>
                                <option value="city">City</option>
                                <option value="panchayat">Panchayat</option>
                                <option value="village">Village</option>
                            </select>
                        </div>
                        <div class="row g-3 from-location-fields">
                            <div class="col-md-4">
                                <label class="form-label">State</label>
                                <select id="from_state_id" name="from_state_id" class="form-select">
                                    <option value="">Select State</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">District</label>
                                <select id="from_district_id" name="from_district_id" class="form-select">
                                    <option value="">Select District</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">City</label>
                                <select id="from_city_id" name="from_city_id" class="form-select">
                                    <option value="">Select City</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Panchayat Name</label>
                                <input type="text" id="from_panchayat_name" name="from_panchayat_name" class="form-control" placeholder="Enter Panchayat" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Village Name</label>
                                <input type="text" id="from_village_name" name="from_village_name" class="form-control" placeholder="Enter Village" />
                            </div>
                        </div>
                    </div>

                    <div class="border rounded p-3 mb-3">
                        <h6 class="mb-3">To Level</h6>
                        <div class="mb-3">
                            <label for="to_level_type" class="form-label">To Level Type</label>
                            <select id="to_level_type" name="to_level_type" class="form-select" required>
                                <option value="">Select Level</option>
                                <option value="state">State</option>
                                <option value="district">District</option>
                                <option value="city">City</option>
                                <option value="panchayat">Panchayat</option>
                                <option value="village">Village</option>
                            </select>
                        </div>
                        <div class="row g-3 to-location-fields">
                            <div class="col-md-4">
                                <label class="form-label">State</label>
                                <select id="to_state_id" name="to_state_id" class="form-select">
                                    <option value="">Select State</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">District</label>
                                <select id="to_district_id" name="to_district_id" class="form-select">
                                    <option value="">Select District</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">City</label>
                                <select id="to_city_id" name="to_city_id" class="form-select">
                                    <option value="">Select City</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Panchayat Name</label>
                                <input type="text" id="to_panchayat_name" name="to_panchayat_name" class="form-control" placeholder="Enter Panchayat" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Village Name</label>
                                <input type="text" id="to_village_name" name="to_village_name" class="form-control" placeholder="Enter Village" />
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Transfer Stock</button>
                        <button type="reset" class="btn btn-light">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Stock Transfers</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="stock-transfers-table" class="table table-striped table-bordered w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Category</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const routes = {
        productsByCategory: function(categoryId) {
            return "{{ route('admin.products.by-category', ':id') }}".replace(':id', categoryId);
        },
        list: "{{ route('admin.stock-transfers.index') }}",
        store: "{{ route('admin.stock-transfers.store') }}",
        apiStates: "{{ url('/api/location/states') }}",
        apiDistricts: function(stateId) { return "{{ url('/api/location/districts') }}" + "?state_id=" + stateId; },
        apiCities: function(districtId) { return "{{ url('/api/location/cities') }}" + "?district_id=" + districtId; }
    };

    function populateProducts(categoryId) {
        $('#product_id').empty().append('<option value="">Select Product</option>');
        if (!categoryId) return;
        $.getJSON(routes.productsByCategory(categoryId), function(res) {
            const products = res.data || res; // handle either structure
            products.forEach(p => {
                $('#product_id').append(`<option value="${p.id}">${p.name}</option>`);
            });
        }).fail(() => {
            alert('Failed to load products for selected category');
        });
    }

    function populateStates(selectId) {
        $.getJSON(routes.apiStates, function(res) {
            const $el = $(selectId);
            $el.empty().append('<option value="">Select State</option>');
            (res.data || res).forEach(s => $el.append(`<option value="${s.id}">${s.state_name || s.name}</option>`));
        });
    }

    function populateDistricts(stateId, selectId) {
        const $el = $(selectId);
        $el.empty().append('<option value="">Select District</option>');
        if (!stateId) return;
        $.getJSON(routes.apiDistricts(stateId), function(res) {
            (res.data || res).forEach(d => $el.append(`<option value="${d.id}">${d.district_name || d.name}</option>`));
        });
    }

    function populateCities(districtId, selectId) {
        const $el = $(selectId);
        $el.empty().append('<option value="">Select City</option>');
        if (!districtId) return;
        $.getJSON(routes.apiCities(districtId), function(res) {
            (res.data || res).forEach(c => $el.append(`<option value="${c.id}">${c.city_name || c.name}</option>`));
        });
    }

    function toggleLevelFields(prefix) {
        const levelType = $(`#${prefix}_level_type`).val();
        const showState = levelType === 'state' || levelType === 'district' || levelType === 'city';
        const showDistrict = levelType === 'district' || levelType === 'city';
        const showCity = levelType === 'city';
        const showPanchayat = levelType === 'panchayat';
        const showVillage = levelType === 'village';

        $(`#${prefix}_state_id`).closest('.col-md-4').toggle(showState);
        $(`#${prefix}_district_id`).closest('.col-md-4').toggle(showDistrict);
        $(`#${prefix}_city_id`).closest('.col-md-4').toggle(showCity);
        $(`#${prefix}_panchayat_name`).closest('.col-md-6').toggle(showPanchayat);
        $(`#${prefix}_village_name`).closest('.col-md-6').toggle(showVillage);
    }

    $(document).ready(function() {
        // Populate states initially for both sections
        populateStates('#from_state_id');
        populateStates('#to_state_id');

        // Category -> Products
        $('#product_category_id').on('change', function() {
            populateProducts(this.value);
        });

        // From level toggles and dependent selects
        $('#from_level_type').on('change', function() { toggleLevelFields('from'); });
        $('#from_state_id').on('change', function() {
            populateDistricts(this.value, '#from_district_id');
            $('#from_city_id').empty().append('<option value="">Select City</option>');
        });
        $('#from_district_id').on('change', function() {
            populateCities(this.value, '#from_city_id');
        });

        // To level toggles and dependent selects
        $('#to_level_type').on('change', function() { toggleLevelFields('to'); });
        $('#to_state_id').on('change', function() {
            populateDistricts(this.value, '#to_district_id');
            $('#to_city_id').empty().append('<option value="">Select City</option>');
        });
        $('#to_district_id').on('change', function() {
            populateCities(this.value, '#to_city_id');
        });

        // Initialize visibility
        toggleLevelFields('from');
        toggleLevelFields('to');

        // DataTable
        const table = $('#stock-transfers-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: routes.list,
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'category', name: 'category' },
                { data: 'product', name: 'product' },
                { data: 'quantity', name: 'quantity' },
                { data: 'from_level', name: 'from_level' },
                { data: 'to_level', name: 'to_level' },
                { data: 'created_at', name: 'created_at' },
            ]
        });

        // Submit form
        $('#stock-transfer-form').on('submit', function(e) {
            e.preventDefault();
            const formData = $(this).serialize();
            $.ajax({
                url: routes.store,
                method: 'POST',
                data: formData,
                success: function(res) {
                    if (res.success) {
                        alert(res.message || 'Transfer successful');
                        $('#stock-transfer-form')[0].reset();
                        table.ajax.reload(null, false);
                    } else {
                        alert(res.message || 'Failed');
                    }
                },
                error: function(xhr) {
                    let msg = 'Failed to transfer stock';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        const errors = xhr.responseJSON.errors;
                        msg = Object.values(errors).map(arr => arr.join(', ')).join('\n');
                    }
                    alert(msg);
                }
            });
        });
    });
</script>
@endpush
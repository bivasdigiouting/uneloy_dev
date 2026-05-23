@extends('ecard.ecard')

@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<style>
  #ecardPreviewFrame{width:100%; height:70vh; border:0; background:#fff;}
  .dt-row-select{display:flex; align-items:center; justify-content:center;}
  .dt-row-select input{width:18px; height:18px;}
</style>
<div class="container-fluid">
  <div class="row mb-3">
    <div class="col">
      <h4 class="mb-1">E-Card Print Report</h4>
      <p class="text-muted">Find users and generate professional printable e-cards. Use filters to narrow results.</p>
    </div>
  </div>

  <div class="card mb-4">
    <div class="card-body">
      <form id="filters" class="row g-3">
        <div class="col-md-2">
          <label class="form-label">From date</label>
          <input type="date" class="form-control" name="from_date">
        </div>
        <div class="col-md-2">
          <label class="form-label">To date</label>
          <input type="date" class="form-control" name="to_date">
        </div>
        <div class="col-md-2">
          <label class="form-label">Status</label>
          <select class="form-select" name="status">
            <option value="">All</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
        </div>
        <div class="col-md-2">
          <label class="form-label">State</label>
          <select class="form-select" name="state" id="state">
            <option value="">All</option>
            @foreach($states as $s)
              <option value="{{ $s->name }}" data-id="{{ $s->id }}">{{ $s->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2">
          <label class="form-label">District</label>
          <select class="form-select" name="district" id="district">
            <option value="">All</option>
          </select>
        </div>
        <div class="col-md-2">
          <label class="form-label">City</label>
          <select class="form-select" name="city" id="city">
            <option value="">All</option>
          </select>
        </div>

        <div class="col-md-3">
          <label class="form-label">Search by name</label>
          <input type="text" class="form-control" name="name" placeholder="Name">
        </div>
        <div class="col-md-3">
          <label class="form-label">Email</label>
          <input type="text" class="form-control" name="email" placeholder="Email">
        </div>
        <div class="col-md-3">
          <label class="form-label">Mobile</label>
          <input type="text" class="form-control" name="mobile" placeholder="Mobile">
        </div>
        <div class="col-md-3 d-flex align-items-end">
          <button type="button" id="applyFilters" class="btn btn-primary me-2">Apply</button>
          <button type="button" id="resetFilters" class="btn btn-secondary">Reset</button>
        </div>
      </form>
    </div>
  </div>

  <div class="card">
    <div class="card-body">
      <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div class="text-muted small" id="selectedCount">Selected: 0</div>
        <div class="d-flex gap-2">
          <button type="button" class="btn btn-sm btn-outline-primary" id="btnPreviewSelected" disabled>
            <i class="fas fa-eye me-1"></i> Preview Selected
          </button>
          <a class="btn btn-sm btn-primary disabled" id="btnDownloadSelected" href="#" target="_blank" aria-disabled="true">
            <i class="fas fa-download me-1"></i> Download Selected
          </a>
        </div>
      </div>
      <table id="usersTable" class="table table-striped table-hover w-100 align-middle">
        <thead>
          <tr>
            <th style="width:44px;">
              <div class="dt-row-select">
                <input type="checkbox" id="selectAllRows">
              </div>
            </th>
            <th>User ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>Location</th>
            <th>Status</th>
            <th>Created</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
</div>

<div class="modal fade" id="ecardPreviewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">E-Card Preview</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-0">
        <iframe id="ecardPreviewFrame" src=""></iframe>
      </div>
      <div class="modal-footer">
        <div class="me-auto text-muted small" id="previewCounter"></div>
        <button type="button" class="btn btn-outline-secondary" id="btnPrevCard">Left</button>
        <button type="button" class="btn btn-outline-secondary" id="btnNextCard">Right</button>
        <a class="btn btn-outline-primary" id="btnOpenCurrent" href="#" target="_blank">Open</a>
        <a class="btn btn-outline-primary d-none" id="btnDownloadAll" href="#" target="_blank">Download All</a>
        <a class="btn btn-primary" id="btnDownloadCurrent" href="#" target="_blank">Download</a>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
  const districtsUrl = "{{ route('ecard.locations.districts') }}";
  const citiesUrl = "{{ route('ecard.locations.cities') }}";
  const dataUrl = "{{ route('ecard.users.report.data') }}";
  const bulkUrlBase = "{{ route('ecard.users.report.print.bulk') }}";
  const printUrlBase = "{{ url('/ecard/users/report') }}";

  function loadDistricts() {
    const stateId = document.querySelector('#state').selectedOptions[0]?.dataset.id || '';
    fetch(districtsUrl + '?state_id=' + encodeURIComponent(stateId))
      .then(r => r.json())
      .then(items => {
        const sel = document.querySelector('#district');
        sel.innerHTML = '<option value="">All</option>';
        items.forEach(i => sel.insertAdjacentHTML('beforeend', `<option value="${i.name}" data-id="${i.id}">${i.name}</option>`));
        document.querySelector('#city').innerHTML = '<option value="">All</option>';
      });
  }

  function loadCities() {
    const districtId = document.querySelector('#district').selectedOptions[0]?.dataset.id || '';
    fetch(citiesUrl + '?district_id=' + encodeURIComponent(districtId))
      .then(r => r.json())
      .then(items => {
        const sel = document.querySelector('#city');
        sel.innerHTML = '<option value="">All</option>';
        items.forEach(i => sel.insertAdjacentHTML('beforeend', `<option value="${i.name}" data-id="${i.id}">${i.name}</option>`));
      });
  }

  document.querySelector('#state').addEventListener('change', loadDistricts);
  document.querySelector('#district').addEventListener('change', loadCities);

  const selectedIds = new Set();
  let previewIds = [];
  let previewIndex = 0;
  const modalEl = document.getElementById('ecardPreviewModal');
  let previewModal = null;

  function ensurePreviewModal() {
    if (! previewModal) {
      previewModal = new bootstrap.Modal(modalEl);
    }

    return previewModal;
  }

  function setSelectedCount() {
    const n = selectedIds.size;
    document.getElementById('selectedCount').textContent = `Selected: ${n}`;
    const previewBtn = document.getElementById('btnPreviewSelected');
    previewBtn.disabled = n === 0;
    const dl = document.getElementById('btnDownloadSelected');
    if (n === 0) {
      dl.classList.add('disabled');
      dl.setAttribute('aria-disabled', 'true');
      dl.href = '#';
    } else {
      const ids = Array.from(selectedIds).join(',');
      dl.classList.remove('disabled');
      dl.setAttribute('aria-disabled', 'false');
      dl.href = `${bulkUrlBase}?ids=${encodeURIComponent(ids)}&autoprint=1`;
    }
  }

  function openPreview(ids, startAt = 0) {
    previewIds = ids;
    previewIndex = Math.max(0, Math.min(startAt, previewIds.length - 1));
    updatePreview();
    ensurePreviewModal().show();
  }

  function updatePreview() {
    const id = previewIds[previewIndex];
    const url = `${printUrlBase}/${encodeURIComponent(id)}/ecard`;
    const frame = document.getElementById('ecardPreviewFrame');
    frame.src = url || '';
    document.getElementById('previewCounter').textContent = previewIds.length > 1 ? `${previewIndex + 1} / ${previewIds.length}` : '';
    document.getElementById('btnPrevCard').disabled = previewIndex <= 0;
    document.getElementById('btnNextCard').disabled = previewIndex >= previewIds.length - 1;
    const open = document.getElementById('btnOpenCurrent');
    const download = document.getElementById('btnDownloadCurrent');
    const downloadAll = document.getElementById('btnDownloadAll');
    open.href = url || '#';
    download.href = url ? `${url}?autoprint=1` : '#';
    if (previewIds.length > 1) {
      downloadAll.classList.remove('d-none');
      downloadAll.href = `${bulkUrlBase}?ids=${encodeURIComponent(previewIds.join(','))}&autoprint=1`;
    } else {
      downloadAll.classList.add('d-none');
      downloadAll.href = '#';
    }
  }

  let dt;
  function initTable() {
    if (dt) { dt.destroy(); }
    dt = $('#usersTable').DataTable({
      processing: true,
      serverSide: true,
      searching: false,
      paging: true,
      pageLength: 25,
      order: [[7, 'desc']],
      language: { emptyTable: 'No users found for selected filters' },
      ajax: {
        url: dataUrl,
        data: function(d) {
          const form = document.getElementById('filters');
          const fd = new FormData(form);
          for (const [k,v] of fd.entries()) { d[k] = v; }
        }
      },
      columns: [
        { data: 'select', orderable: false, searchable: false, render: function(val){
            const checked = selectedIds.has(String(val)) ? 'checked' : '';
            return `<div class="dt-row-select"><input type="checkbox" class="row-select" value="${val}" ${checked}></div>`;
          } },
        { data: 'user_id' },
        { data: 'name' },
        { data: 'email' },
        { data: 'mobile' },
        { data: 'location' },
        { data: 'status', render: function(val){
            const v = (val || '').toString().toLowerCase();
            const cls = v === 'active' || v === '1' ? 'bg-success' : 'bg-secondary';
            const label = v === 'active' || v === '1' ? 'Active' : (v === 'inactive' || v === '0' ? 'Inactive' : (val || '—'));
            return `<span class="badge ${cls}">${label}</span>`;
          } },
        { data: 'created' },
        { data: 'print_url', orderable: false, searchable: false, render: function(val, type, row){
            return `<button type="button" class="btn btn-sm btn-primary btn-preview" data-id="${row.id}" data-url="${val}"><i class="fas fa-print me-1"></i> Print</button>`;
          }
        }
      ],
      drawCallback: function () {
        $('#selectAllRows').prop('checked', false);
        setSelectedCount();
      }
    });
  }

  document.getElementById('applyFilters').addEventListener('click', function(){
    initTable();
  });
  document.getElementById('resetFilters').addEventListener('click', function(){
    document.getElementById('filters').reset();
    document.querySelector('#district').innerHTML = '<option value="">All</option>';
    document.querySelector('#city').innerHTML = '<option value="">All</option>';
    initTable();
  });

  document.getElementById('selectAllRows').addEventListener('change', function () {
    const checked = this.checked;
    document.querySelectorAll('#usersTable tbody input.row-select').forEach(cb => {
      cb.checked = checked;
      const id = String(cb.value);
      if (checked) {
        selectedIds.add(id);
      } else {
        selectedIds.delete(id);
      }
    });
    setSelectedCount();
  });

  $(document).on('change', '.row-select', function () {
    const id = String(this.value);
    if (this.checked) {
      selectedIds.add(id);
    } else {
      selectedIds.delete(id);
      $('#selectAllRows').prop('checked', false);
    }
    setSelectedCount();
  });

  $(document).on('click', '.btn-preview', function () {
    const id = String($(this).data('id'));
    openPreview([id], 0);
  });

  document.getElementById('btnPreviewSelected').addEventListener('click', function () {
    const ids = Array.from(selectedIds);
    openPreview(ids, 0);
  });

  document.getElementById('btnPrevCard').addEventListener('click', function () {
    if (previewIndex > 0) {
      previewIndex -= 1;
      updatePreview();
    }
  });

  document.getElementById('btnNextCard').addEventListener('click', function () {
    if (previewIndex < previewIds.length - 1) {
      previewIndex += 1;
      updatePreview();
    }
  });

  $(document).ready(function(){
    initTable();
    setSelectedCount();
  });
</script>
@endsection

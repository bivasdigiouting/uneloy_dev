@extends('ecard.ecard')

@section('title', 'Upload KYC Documents')

@section('content')
<div class="container-fluid py-3">
    <div class="row mb-3">
        <div class="col">
            <h4 class="mb-1"><i class="fas fa-id-card me-2"></i>Upload KYC Documents</h4>
            <p class="text-muted">Upload, update, or delete your KYC files. Drag and drop files into each box or click to select.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('ecard.kyc.store') }}" enctype="multipart/form-data" id="kycForm">
        @csrf
        <div class="row g-3">
            @php
                $items = [
                    ['field' => 'aadhaar_front', 'label' => 'Aadhaar Front'],
                    ['field' => 'aadhaar_back', 'label' => 'Aadhaar Back'],
                    ['field' => 'pan_front', 'label' => 'PAN Front'],
                    ['field' => 'pan_back', 'label' => 'PAN Back'],
                    ['field' => 'cheque_book', 'label' => 'Cheque Book'],
                    ['field' => 'business_document', 'label' => 'Business Document'],
                    ['field' => 'business_photo', 'label' => 'Business Photo'],
                    ['field' => 'signature', 'label' => 'Signature'],
                ];
            @endphp

            @foreach($items as $item)
                @php
                    $field = $item['field'];
                    $label = $item['label'];
                    $current = $kyc && $kyc->{$field} ? $kyc->{$field} : null;
                @endphp
                <div class="col-12 col-md-6 col-xl-3">
                    <div class="card h-100 shadow-sm">
                        <div class="card-header d-flex align-items-center justify-content-between py-2">
                            <span class="fw-semibold">{{ $label }}</span>
                            <small class="text-muted">Max 5MB • jpg/png/webp/pdf</small>
                        </div>
                        <div class="card-body">
                            @php
                                $currentUrl = $current ? asset('storage/'.$current) : null;
                                $isImage = $current && preg_match('/\.(jpg|jpeg|png|webp)$/i', $current);
                                $isPdf = $current && preg_match('/\.(pdf)$/i', $current);
                            @endphp
                            <div class="kyc-dropzone" data-target="{{ $field }}" data-current-url="{{ $isImage ? $currentUrl : '' }}" data-current-pdf="{{ $isPdf ? $currentUrl : '' }}">
                                <input type="file" name="{{ $field }}" id="{{ $field }}" class="d-none" accept=".jpg,.jpeg,.png,.webp,.pdf">
                                <div class="dz-instructions text-center">
                                    <i class="fas fa-cloud-upload-alt fa-2x mb-2 text-secondary"></i>
                                    <div class="fw-semibold">Drag & drop or click to upload</div>
                                    <div class="text-muted small">{{ $label }}</div>
                                </div>
                                <div class="dz-current {{ ($isImage || $isPdf) ? '' : 'd-none' }}">
                                    <div class="dz-current-thumb-wrap mb-2 text-center">
                                        @if($isImage)
                                            <img class="dz-current-thumb" src="{{ $currentUrl }}" alt="{{ $label }} preview" loading="lazy" onerror="this.classList.add('d-none'); this.closest('.dz-current').querySelector('.dz-file-icon').classList.remove('d-none');">
                                        @elseif($isPdf)
                                            <object class="dz-current-pdf" data="{{ $currentUrl }}" type="application/pdf" width="100%" height="180" aria-label="{{ $label }} PDF"></object>
                                        @endif
                                        <div class="dz-file-icon {{ ($isImage || $isPdf) ? 'd-none' : '' }}"><i class="far fa-file fa-2x text-secondary"></i></div>
                                    </div>
                                    <div class="d-flex gap-2 justify-content-center">
                                        @if($current)
                                            <a class="btn btn-sm btn-outline-primary" href="{{ $currentUrl }}" target="_blank">Open</a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-action="delete" data-field="{{ $field }}">Delete current</button>
                                        @endif
                                    </div>
                                </div>
                                <div class="dz-preview d-none">
                                    <div class="dz-preview-thumb-wrap mb-2 text-center">
                                        <img class="dz-preview-thumb d-none" alt="{{ $label }} preview">
                                        <div class="dz-file-icon d-none"><i class="far fa-file fa-2x text-secondary"></i></div>
                                    </div>
                                    <div class="dz-filename small mb-2 text-center"></div>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <button type="button" class="btn btn-sm btn-outline-secondary" data-action="clear">Clear</button>
                                        @if($current)
                                            <a class="btn btn-sm btn-outline-primary" href="{{ $currentUrl }}" target="_blank">View current</a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-action="delete" data-field="{{ $field }}">Delete current</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($current)
                            <div class="card-footer py-2 d-flex justify-content-between align-items-center">
                                <span class="text-success small"><i class="fas fa-check-circle me-1"></i>Uploaded</span>
                                <a href="{{ asset('storage/'.$current) }}" target="_blank" class="small">Open</a>
                            </div>
                        @else
                            <div class="card-footer py-2">
                                <span class="text-muted small">No file uploaded yet</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save Changes</button>
            <a href="{{ route('ecard.dashboard') }}" class="btn btn-outline-secondary">Back to Dashboard</a>
        </div>
    </form>
</div>

<style>
.kyc-dropzone {
    border: 2px dashed #ced4da;
    border-radius: 8px;
    padding: 18px;
    cursor: pointer;
    transition: border-color .2s ease, background-color .2s ease;
    min-height: 140px;
}
.kyc-dropzone.dragover { border-color: #0d6efd; background-color: #f0f6ff; }
.dz-current-thumb, .dz-preview-thumb { max-width: 100%; max-height: 160px; border-radius: 6px; box-shadow: 0 2px 6px rgba(0,0,0,0.08); }
.dz-file-icon { line-height: 1; }
.dz-filename { word-break: break-all; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.kyc-dropzone').forEach(function(zone) {
        const target = zone.getAttribute('data-target');
        const input = document.getElementById(target);
        const preview = zone.querySelector('.dz-preview');
        const filename = zone.querySelector('.dz-filename');
        const clearBtn = zone.querySelector('[data-action="clear"]');
        const deleteBtns = zone.querySelectorAll('[data-action="delete"]');
        const instructions = zone.querySelector('.dz-instructions');
        const current = zone.querySelector('.dz-current');
        const currentThumb = zone.querySelector('.dz-current-thumb');
        const currentUrl = zone.getAttribute('data-current-url');
        const currentPdfUrl = zone.getAttribute('data-current-pdf');
        const previewThumb = zone.querySelector('.dz-preview-thumb');
        const fileIcon = zone.querySelector('.dz-file-icon');

        const showPreview = (name) => {
            if (name) { filename.textContent = name; }
            instructions.classList.add('d-none');
            preview.classList.remove('d-none');
            if (current) { current.classList.add('d-none'); }
        };
        const hidePreview = () => {
            filename.textContent = '';
            preview.classList.add('d-none');
            instructions.classList.remove('d-none');
            if ((currentUrl || currentPdfUrl) && current) { current.classList.remove('d-none'); }
        };

        const setPreviewThumbFromFile = (file) => {
            if (!file) return;
            const isImage = file.type && file.type.startsWith('image/');
            if (isImage) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (previewThumb) {
                        previewThumb.src = e.target.result;
                        previewThumb.classList.remove('d-none');
                    }
                    if (fileIcon) fileIcon.classList.add('d-none');
                };
                reader.readAsDataURL(file);
            } else {
                if (previewThumb) previewThumb.classList.add('d-none');
                if (fileIcon) fileIcon.classList.remove('d-none');
            }
        };

        zone.addEventListener('click', () => input.click());
        zone.addEventListener('dragover', (e) => { e.preventDefault(); zone.classList.add('dragover'); });
        zone.addEventListener('dragleave', () => zone.classList.remove('dragover'));
        zone.addEventListener('drop', (e) => {
            e.preventDefault();
            zone.classList.remove('dragover');
            if (e.dataTransfer.files && e.dataTransfer.files.length) {
                input.files = e.dataTransfer.files;
                showPreview(input.files[0].name);
                setPreviewThumbFromFile(input.files[0]);
            }
        });

        input.addEventListener('change', (e) => {
            if (input.files && input.files.length) {
                showPreview(input.files[0].name);
                setPreviewThumbFromFile(input.files[0]);
            } else {
                hidePreview();
            }
        });

        if (clearBtn) {
            clearBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                input.value = '';
                hidePreview();
            });
        }

        if (deleteBtns && deleteBtns.length) {
            deleteBtns.forEach(function(deleteBtn) {
                deleteBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const field = deleteBtn.getAttribute('data-field');
                    if (!field) return;
                    if (!confirm('Delete current file for ' + field + '?')) return;

                    fetch("{{ route('ecard.kyc.destroy', ['field' => 'FIELD']) }}".replace('FIELD', field), {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    }).then(res => res.json()).then(data => {
                        if (data && data.message) {
                            location.reload();
                        }
                    }).catch(() => {});
                });
            });
        }

        // Show current image preview if available
        // Show current preview (image or PDF) if available
        if ((currentUrl || currentPdfUrl) && current) {
            if (currentUrl && currentThumb) {
                currentThumb.src = currentUrl;
                currentThumb.classList.remove('d-none');
            }
            current.classList.remove('d-none');
            instructions.classList.add('d-none');
        }
    });
});
</script>
@endsection
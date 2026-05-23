@extends('layouts.public')

@section('content')
<main class="container my-4" style="min-height: 60vh;">
    <div class="row">
        <div class="col-12 mb-3">
            <div class="d-flex flex-wrap align-items-center justify-content-between">
                <div>
                    <h1 class="mb-1">{{ $album->title }}</h1>
                    <p class="text-muted mb-0">Click any image to view in full size.</p>
                </div>
                <div class="mt-2 mt-sm-0">
                    <a href="{{ route('frontend.gallery') }}" class="btn btn-outline-secondary btn-sm">Back to Gallery</a>
                </div>
            </div>
        </div>

        @forelse($images as $index => $img)
            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-3">
                <a href="javascript:void(0);" class="gallery-item" data-index="{{ $index }}" data-src="{{ $img->image_url }}" data-title="{{ $album->title }}">
                    <div class="card border-0 shadow-sm" style="overflow:hidden;">
                        <img src="{{ $img->image_url }}" alt="{{ $album->title }}" style="width:100%;height:200px;object-fit:cover;">
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">No images available in this gallery.</div>
            </div>
        @endforelse
    </div>
</main>

<div class="modal fade" id="galleryLightbox" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="galleryLightboxTitle">{{ $album->title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center" style="position:relative;">
                <button type="button" class="btn btn-default" id="galleryPrev" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);">
                    <i class="fa fa-chevron-left"></i>
                </button>
                <img id="galleryLightboxImg" src="" alt="Gallery Preview" style="max-width:100%;max-height:70vh;object-fit:contain;">
                <button type="button" class="btn btn-default" id="galleryNext" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);">
                    <i class="fa fa-chevron-right"></i>
                </button>
            </div>
            <div class="modal-footer" style="justify-content:space-between;display:flex;">
                <div class="text-muted" id="galleryCounter"></div>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    var items = [];
    $('.gallery-item').each(function () {
        items.push({
            src: $(this).data('src'),
            title: $(this).data('title') || 'Gallery'
        });
    });

    var currentIndex = 0;

    function render() {
        if (!items.length) return;
        var item = items[currentIndex];
        $('#galleryLightboxImg').attr('src', item.src);
        $('#galleryLightboxTitle').text(item.title);
        $('#galleryCounter').text((currentIndex + 1) + ' / ' + items.length);
    }

    function openAt(index) {
        currentIndex = index;
        render();
        $('#galleryLightbox').modal('show');
    }

    function prev() {
        currentIndex = (currentIndex - 1 + items.length) % items.length;
        render();
    }

    function next() {
        currentIndex = (currentIndex + 1) % items.length;
        render();
    }

    $(document).on('click', '.gallery-item', function () {
        openAt(parseInt($(this).data('index'), 10) || 0);
    });

    $('#galleryPrev').on('click', function () { prev(); });
    $('#galleryNext').on('click', function () { next(); });

    $(document).on('keydown', function (e) {
        if (!$('#galleryLightbox').hasClass('in')) return;
        if (e.key === 'ArrowLeft') prev();
        if (e.key === 'ArrowRight') next();
    });
})();
</script>
@endsection


@extends('layouts.public')

@section('content')
<main class="faq-section" style="min-height: 60vh; background-color: #f8f9fa; padding: 60px 0;">
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8 text-center">
                <span class="text-uppercase fw-bold" style="color: #ff6a00; letter-spacing: 2px;">Find Answers</span>
                <h1 class="display-5 fw-bold text-dark mb-3 mt-2">Frequently Asked Questions</h1>
                <p class="lead text-secondary">Everything you need to know about our services, portals, and policies. Can't find an answer? Feel free to reach out to our team.</p>
                <div class="mt-4">
                    <hr style="width: 80px; height: 3px; background-color: #ff6a00; border: none; margin: 0 auto; opacity: 1; border-radius: 5px;">
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-9">
                @if($faqs->count() > 0)
                    <div class="accordion custom-accordion" id="faqsAccordion">
                        @foreach($faqs as $index => $faq)
                            <div class="accordion-item mb-4 border-0 shadow-sm rounded-3" style="overflow: hidden; background: #fff;">
                                <h2 class="accordion-header" id="heading-{{ $faq->id }}">
                                    <button class="accordion-button {{ $index == 0 ? '' : 'collapsed' }} fw-semibold fs-5 text-dark" style="padding: 20px 25px; box-shadow: none;" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $faq->id }}" aria-expanded="{{ $index == 0 ? 'true' : 'false' }}" aria-controls="collapse-{{ $faq->id }}">
                                        {{ $faq->question }}
                                    </button>
                                </h2>
                                <div id="collapse-{{ $faq->id }}" class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}" aria-labelledby="heading-{{ $faq->id }}" data-bs-parent="#faqsAccordion">
                                    <div class="accordion-body text-secondary lh-lg" style="padding: 0 25px 25px 25px; font-size: 1.05rem;">
                                        {!! nl2br(e($faq->answer)) !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center p-5 bg-white shadow-sm rounded-3">
                        <i class="ti ti-help text-muted mb-3" style="font-size: 4rem;"></i>
                        <h3 class="fw-bold text-dark">No FAQs Available Right Now</h3>
                        <p class="text-secondary mt-2">We are currently updating our knowledge base. Please check back later.</p>
                    </div>
                @endif
            </div>
        </div>
        
        <div class="row justify-content-center mt-5">
            <div class="col-lg-8 text-center">
                <div class="p-4 bg-white shadow-sm rounded-3 border-top border-3" style="border-top-color: #ff6a00 !important;">
                    <h4 class="fw-bold mb-2">Still have questions?</h4>
                    <p class="text-secondary mb-3">If you cannot find answer to your question in our FAQ, you can always contact us. We will answer to you shortly!</p>
                    <a href="{{ route('contact-us') }}" class="btn text-white px-4 py-2" style="background-color: #ff6a00; font-weight: 500; border-radius: 5px;">Contact Support</a>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    /* Premium Accordion Styling */
    .custom-accordion .accordion-button {
        border: none !important;
        outline: none !important;
        box-shadow: none !important;
    }
    .custom-accordion .accordion-item {
        border: 1px solid rgba(0,0,0,0.05) !important;
    }
    .custom-accordion .accordion-button:not(.collapsed) {
        color: #ff6a00 !important;
        background-color: #fff !important;
        box-shadow: none;
    }
    .custom-accordion .accordion-button:focus {
        border-color: rgba(255, 106, 0, 0.2);
        box-shadow: 0 0 0 0.25rem rgba(255, 106, 0, 0.1);
    }
    .custom-accordion .accordion-button::after {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%236c757d'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
    }
    .custom-accordion .accordion-button:not(.collapsed)::after {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23ff6a00'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
    }
    .custom-accordion .accordion-item {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .custom-accordion .accordion-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.08) !important;
    }
</style>
@endsection

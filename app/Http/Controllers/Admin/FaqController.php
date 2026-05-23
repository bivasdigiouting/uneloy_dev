<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\FaqRepositoryInterface;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    protected FaqRepositoryInterface $faqRepository;

    public function __construct(FaqRepositoryInterface $faqRepository)
    {
        $this->faqRepository = $faqRepository;
    }

    public function index()
    {
        $faqs = $this->faqRepository->getAllFaqs();
        return view('admin.about-us.faqs.index', compact('faqs'));
    }

    public function create()
    {
        return view('admin.about-us.faqs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'status' => 'required|boolean',
        ]);

        $this->faqRepository->createFaq($validated);

        return redirect()->route('admin.about-us.faqs.index')->with('success', 'FAQ created successfully.');
    }

    public function edit($id)
    {
        $faq = $this->faqRepository->getFaqById($id);
        return view('admin.about-us.faqs.edit', compact('faq'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'status' => 'required|boolean',
            'order' => 'nullable|integer',
        ]);

        $this->faqRepository->updateFaq($id, $validated);

        return redirect()->route('admin.about-us.faqs.index')->with('success', 'FAQ updated successfully.');
    }

    public function destroy($id)
    {
        $this->faqRepository->deleteFaq($id);
        return redirect()->route('admin.about-us.faqs.index')->with('success', 'FAQ deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $this->faqRepository->toggleStatus($id);
        return redirect()->route('admin.about-us.faqs.index')->with('success', 'FAQ status updated successfully.');
    }
}

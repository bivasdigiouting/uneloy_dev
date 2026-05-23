<?php

namespace App\Repositories;

use App\Models\Faq;
use App\Repositories\Interfaces\FaqRepositoryInterface;

class FaqRepository implements FaqRepositoryInterface
{
    public function getAllFaqs()
    {
        return Faq::orderBy('order', 'asc')->get();
    }

    public function getActiveFaqs()
    {
        return Faq::where('status', 1)->orderBy('order', 'asc')->get();
    }

    public function getFaqById($id)
    {
        return Faq::findOrFail($id);
    }

    public function createFaq(array $data)
    {
        $maxOrder = Faq::max('order') ?? 0;
        $data['order'] = $maxOrder + 1;
        
        return Faq::create($data);
    }

    public function updateFaq($id, array $data)
    {
        $faq = Faq::findOrFail($id);
        $faq->update($data);
        return $faq;
    }

    public function deleteFaq($id)
    {
        $faq = Faq::findOrFail($id);
        return $faq->delete();
    }

    public function toggleStatus($id)
    {
        $faq = Faq::findOrFail($id);
        $faq->status = !$faq->status;
        $faq->save();
        return $faq;
    }
}

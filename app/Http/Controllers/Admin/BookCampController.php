<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\BookCampRepositoryInterface;
use Illuminate\Http\Request;

class BookCampController extends Controller
{
    protected BookCampRepositoryInterface $bookCampRepository;

    public function __construct(BookCampRepositoryInterface $bookCampRepository)
    {
        $this->bookCampRepository = $bookCampRepository;
    }

    /**
     * Show the Book Camp edit page.
     */
    public function edit()
    {
        $bookCamp = $this->bookCampRepository->getBookCamp();

        return view('admin.benefits.book-camp', compact('bookCamp'));
    }

    /**
     * Update Book Camp content
     */
    public function update(Request $request)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'text_header' => 'required|string|max:255',
            'text_description' => 'required|string',
            'footer_short_description' => 'required|string',
        ]);

        try {
            $this->bookCampRepository->update($request->all());

            return redirect()->route('admin.benefits.book-camp.edit')
                ->with('success', 'Book Camp updated successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.benefits.book-camp.edit')
                ->with('error', 'Error updating Book Camp: '.$e->getMessage());
        }
    }
}

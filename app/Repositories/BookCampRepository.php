<?php

namespace App\Repositories;

use App\Models\BookCamp;
use App\Repositories\Interfaces\BookCampRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class BookCampRepository implements BookCampRepositoryInterface
{
    protected BookCamp $model;

    public function __construct(BookCamp $model)
    {
        $this->model = $model;
    }

    /**
     * Get the book camp record (should be only one)
     */
    public function getBookCamp(): ?BookCamp
    {
        return $this->model->first();
    }

    /**
     * Update book camp information
     */
    public function update(array $data): BookCamp
    {
        $bookCamp = $this->getBookCamp();

        if (! $bookCamp) {
            $bookCamp = $this->createIfNotExists();
        }

        // Handle image upload
        if (isset($data['image']) && $data['image']) {
            // Delete old image if exists
            if ($bookCamp->image && Storage::disk('public')->exists($bookCamp->image)) {
                Storage::disk('public')->delete($bookCamp->image);
            }

            // Store new image
            $data['image'] = $data['image']->store('book-camp-images', 'public');
        }

        $bookCamp->update($data);

        return $bookCamp->fresh();
    }

    /**
     * Create book camp record if it doesn't exist
     */
    public function createIfNotExists(): BookCamp
    {
        return $this->model->firstOrCreate(
            [],
            [
                'text_header' => '',
                'text_description' => '',
                'footer_short_description' => '',
            ]
        );
    }
}

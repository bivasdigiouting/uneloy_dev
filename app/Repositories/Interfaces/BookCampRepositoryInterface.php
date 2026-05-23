<?php

namespace App\Repositories\Interfaces;

use App\Models\BookCamp;

interface BookCampRepositoryInterface
{
    /**
     * Get the book camp record (should be only one)
     */
    public function getBookCamp(): ?BookCamp;

    /**
     * Update book camp information
     */
    public function update(array $data): BookCamp;

    /**
     * Create book camp record if it doesn't exist
     */
    public function createIfNotExists(): BookCamp;
}

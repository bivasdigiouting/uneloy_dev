<?php

namespace App\Repositories\Interfaces;

use App\Models\Hotel;

interface HotelRepositoryInterface
{
    /**
     * Get the hotel data.
     */
    public function getHotel(): ?Hotel;

    /**
     * Update hotel information.
     */
    public function update(array $data): Hotel;

    /**
     * Create hotel record if it doesn't exist.
     */
    public function createIfNotExists(): Hotel;
}

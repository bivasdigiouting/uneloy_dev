<?php

namespace App\Repositories\Interfaces;

use App\Models\CityDevelopment;

interface CityDevelopmentRepositoryInterface
{
    /**
     * Get the city development data.
     */
    public function getCityDevelopment(): ?CityDevelopment;

    /**
     * Update city development information.
     */
    public function update(array $data): CityDevelopment;

    /**
     * Create city development record if it doesn't exist.
     */
    public function createIfNotExists(): CityDevelopment;
}

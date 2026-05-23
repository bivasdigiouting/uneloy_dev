<?php

namespace App\Repositories\Interfaces;

use App\Models\AboutUs;

interface AboutUsRepositoryInterface
{
    /**
     * Get the about us record (should be only one)
     */
    public function getAboutUs(): ?AboutUs;

    /**
     * Update about us information
     */
    public function update(array $data): AboutUs;

    /**
     * Create about us record if it doesn't exist
     */
    public function createIfNotExists(): AboutUs;
}

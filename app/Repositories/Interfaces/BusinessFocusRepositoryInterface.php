<?php

namespace App\Repositories\Interfaces;

use App\Models\BusinessFocus;

interface BusinessFocusRepositoryInterface
{
    /**
     * Get the business focus record (should be only one)
     */
    public function getBusinessFocus(): ?BusinessFocus;

    /**
     * Update business focus information
     */
    public function update(array $data): BusinessFocus;

    /**
     * Create business focus record if it doesn't exist
     */
    public function createIfNotExists(): BusinessFocus;
}

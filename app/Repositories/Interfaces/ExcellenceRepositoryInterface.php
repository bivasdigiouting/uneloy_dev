<?php

namespace App\Repositories\Interfaces;

interface ExcellenceRepositoryInterface
{
    /**
     * Get the single Excellence record.
     */
    public function getExcellence();

    /**
     * Update or create the Excellence record.
     *
     * @param array $data
     */
    public function updateExcellence(array $data);
}

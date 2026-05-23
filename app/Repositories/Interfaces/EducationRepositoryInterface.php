<?php

namespace App\Repositories\Interfaces;

use App\Models\Education;

interface EducationRepositoryInterface
{
    /**
     * Get the education data.
     */
    public function getEducation(): ?Education;

    /**
     * Update education information.
     */
    public function update(array $data): Education;

    /**
     * Create education record if it doesn't exist.
     */
    public function createIfNotExists(): Education;
}

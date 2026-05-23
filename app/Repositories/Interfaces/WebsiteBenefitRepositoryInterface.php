<?php

namespace App\Repositories\Interfaces;

interface WebsiteBenefitRepositoryInterface
{
    /**
     * Get all website benefits
     */
    public function getAll();

    /**
     * Get active website benefits ordered by sequence
     */
    public function getActive();

    /**
     * Find website benefit by ID
     */
    public function findById($id);

    /**
     * Create new website benefit
     */
    public function create(array $data);

    /**
     * Update website benefit
     */
    public function update($id, array $data);

    /**
     * Delete website benefit
     */
    public function delete($id);

    /**
     * Get website benefits for DataTables
     */
    public function getForDataTable();

    /**
     * Update sequence for multiple benefits
     */
    public function updateSequences(array $sequences);
}

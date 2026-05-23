<?php

namespace App\Repositories\Interfaces;

interface VendorTypeRepositoryInterface
{
    /**
     * Get all vendor types
     */
    public function getAll();

    /**
     * Get vendor type by ID
     */
    public function findById($id);

    /**
     * Create a new vendor type
     */
    public function create(array $data);

    /**
     * Update vendor type
     */
    public function update($id, array $data);

    /**
     * Delete vendor type
     */
    public function delete($id);

    /**
     * Get active vendor types
     */
    public function getActive();

    /**
     * Toggle vendor type status
     */
    public function toggleStatus($id);

    /**
     * Get vendor types for DataTables
     */
    public function getForDataTables();

    /**
     * Check if vendor type name exists
     */
    public function existsByName($name, $excludeId = null);

    /**
     * Get vendor types count by status
     */
    public function getCountByStatus($status = null);
}

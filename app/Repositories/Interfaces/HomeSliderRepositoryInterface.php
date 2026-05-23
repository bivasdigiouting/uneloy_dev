<?php

namespace App\Repositories\Interfaces;

use App\Models\HomeSlider;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface HomeSliderRepositoryInterface
{
    /**
     * Get all home sliders
     */
    public function all(): Collection;

    /**
     * Get paginated home sliders
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    /**
     * Find home slider by ID
     */
    public function find(int $id): ?HomeSlider;

    /**
     * Create new home slider
     */
    public function create(array $data): HomeSlider;

    /**
     * Update home slider
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete home slider
     */
    public function delete(int $id): bool;

    /**
     * Get active home sliders
     */
    public function getActive(): Collection;

    /**
     * Get home sliders for portal display
     */
    public function getForPortal(): Collection;

    /**
     * Get home sliders ordered by sequence
     */
    public function getOrdered(): Collection;

    /**
     * Toggle home slider status
     */
    public function toggleStatus(int $id): bool;

    /**
     * Get home sliders for DataTables
     */
    public function getForDataTables();

    /**
     * Get next sequence number
     */
    public function getNextSequence(): int;
}

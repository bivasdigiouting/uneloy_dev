<?php

namespace App\Repositories;

use App\Models\HomeSlider;
use App\Repositories\Interfaces\HomeSliderRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class HomeSliderRepository implements HomeSliderRepositoryInterface
{
    protected HomeSlider $model;

    public function __construct(HomeSlider $model)
    {
        $this->model = $model;
    }

    /**
     * Get all home sliders
     */
    public function all(): Collection
    {
        return $this->model->ordered()->get();
    }

    /**
     * Get paginated home sliders
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->ordered()->paginate($perPage);
    }

    /**
     * Find home slider by ID
     */
    public function find(int $id): ?HomeSlider
    {
        return $this->model->find($id);
    }

    /**
     * Create new home slider
     */
    public function create(array $data): HomeSlider
    {
        return $this->model->create($data);
    }

    /**
     * Update home slider
     */
    public function update(int $id, array $data): bool
    {
        $homeSlider = $this->find($id);
        if (! $homeSlider) {
            return false;
        }

        return $homeSlider->update($data);
    }

    /**
     * Delete home slider
     */
    public function delete(int $id): bool
    {
        $homeSlider = $this->find($id);
        if (! $homeSlider) {
            return false;
        }

        return $homeSlider->delete();
    }

    /**
     * Get active home sliders
     */
    public function getActive(): Collection
    {
        return $this->model->active()->ordered()->get();
    }

    /**
     * Get home sliders for portal display
     */
    public function getForPortal(): Collection
    {
        return $this->model->active()->showOnPortal()->ordered()->get();
    }

    /**
     * Get home sliders ordered by sequence
     */
    public function getOrdered(): Collection
    {
        return $this->model->ordered()->get();
    }

    /**
     * Toggle home slider status
     */
    public function toggleStatus(int $id): bool
    {
        $homeSlider = $this->find($id);
        if (! $homeSlider) {
            return false;
        }

        $homeSlider->is_active = ! $homeSlider->is_active;

        return $homeSlider->save();
    }

    /**
     * Get home sliders for DataTables
     */
    public function getForDataTables()
    {
        return $this->model->select([
            'id',
            'image',
            'text_header',
            'text_description',
            'show_on_portal',
            'sequence_no',
            'is_active',
            'created_at',
        ])->ordered();
    }

    /**
     * Get next sequence number
     */
    public function getNextSequence(): int
    {
        $maxSequence = $this->model->max('sequence_no');

        return $maxSequence ? $maxSequence + 1 : 1;
    }
}

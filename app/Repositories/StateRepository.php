<?php

namespace App\Repositories;

use App\Models\State;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class StateRepository implements StateRepositoryInterface
{
    protected State $model;

    public function __construct(State $model)
    {
        $this->model = $model;
    }

    /**
     * Get paginated states
     */
    public function getPaginatedStates(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->ordered()->paginate($perPage);
    }

    /**
     * Get active states
     */
    public function getActiveStates(): Collection
    {
        return $this->model->active()->ordered()->get();
    }

    /**
     * Find state by ID
     */
    public function findState(int $id): ?State
    {
        return $this->model->find($id);
    }

    /**
     * Create new state
     */
    public function createState(array $data): State
    {
        return $this->model->create($data);
    }

    /**
     * Update state
     */
    public function updateState(int $id, array $data): bool
    {
        $state = $this->findState($id);

        if (! $state) {
            return false;
        }

        return $state->update($data);
    }

    /**
     * Delete state
     */
    public function deleteState(int $id): bool
    {
        $state = $this->findState($id);

        if (! $state) {
            return false;
        }

        return $state->delete();
    }

    /**
     * Get states for DataTables
     */
    public function getForDataTables()
    {
        return $this->model->select(['id', 'state_name', 'status', 'created_at'])
            ->ordered();
    }

    /**
     * Toggle state status
     */
    public function toggleStatus(int $id): bool
    {
        $state = $this->findState($id);

        if (! $state) {
            return false;
        }

        $newStatus = $state->status === 'active' ? 'inactive' : 'active';

        return $state->update(['status' => $newStatus]);
    }

    /**
     * Get state count
     */
    public function getStateCount(): int
    {
        return $this->model->count();
    }

    /**
     * Get active state count
     */
    public function getActiveStateCount(): int
    {
        return $this->model->active()->count();
    }
}

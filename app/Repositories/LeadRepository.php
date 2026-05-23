<?php

namespace App\Repositories;

use App\Models\Lead;
use App\Repositories\Interfaces\LeadRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class LeadRepository implements LeadRepositoryInterface
{
    protected Lead $model;

    public function __construct(Lead $model)
    {
        $this->model = $model;
    }

    public function getForDataTable(): Builder
    {
        return $this->model->select(['id', 'lead_name', 'is_active', 'created_at']);
    }

    public function findById(int $id): ?Lead
    {
        return $this->model->find($id);
    }

    public function create(array $data): Lead
    {
        return $this->model->create([
            'lead_name' => $data['lead_name'],
            'is_active' => (bool) ($data['is_active'] ?? true),
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $lead = $this->findById($id);
        if (! $lead) {
            return false;
        }

        return $lead->update([
            'lead_name' => $data['lead_name'],
            'is_active' => (bool) ($data['is_active'] ?? $lead->is_active),
        ]);
    }

    public function delete(int $id): bool
    {
        $lead = $this->findById($id);
        if (! $lead) {
            return false;
        }

        return (bool) $lead->delete();
    }

    public function toggleStatus(int $id): bool
    {
        $lead = $this->findById($id);
        if (! $lead) {
            return false;
        }
        $lead->is_active = ! $lead->is_active;

        return $lead->save();
    }
}

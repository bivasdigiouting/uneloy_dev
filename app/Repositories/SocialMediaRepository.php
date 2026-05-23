<?php

namespace App\Repositories;

use App\Models\SocialMedia;
use App\Repositories\Interfaces\SocialMediaRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class SocialMediaRepository implements SocialMediaRepositoryInterface
{
    protected SocialMedia $model;

    public function __construct(SocialMedia $model)
    {
        $this->model = $model;
    }

    public function getForDataTable(): Builder
    {
        return $this->model->select(['id', 'social_media_name', 'is_active', 'created_at']);
    }

    public function findById(int $id): ?SocialMedia
    {
        return $this->model->find($id);
    }

    public function create(array $data): SocialMedia
    {
        return $this->model->create([
            'social_media_name' => $data['social_media_name'],
            'is_active' => isset($data['is_active']) ? (bool) $data['is_active'] : true,
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $sm = $this->findById($id);
        if (! $sm) {
            return false;
        }

        return $sm->update([
            'social_media_name' => $data['social_media_name'],
            'is_active' => isset($data['is_active']) ? (bool) $data['is_active'] : $sm->is_active,
        ]);
    }

    public function delete(int $id): bool
    {
        $sm = $this->findById($id);
        if (! $sm) {
            return false;
        }

        return (bool) $sm->delete();
    }

    public function toggleStatus(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $sm = $this->findById($id);
            if (! $sm) {
                return false;
            }
            $sm->is_active = ! $sm->is_active;

            return $sm->save();
        });
    }
}

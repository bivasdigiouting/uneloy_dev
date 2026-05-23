<?php

namespace App\Repositories;

use App\Models\Banner;
use App\Repositories\Interfaces\BannerRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class BannerRepository implements BannerRepositoryInterface
{
    protected Banner $model;

    public function __construct(Banner $model)
    {
        $this->model = $model;
    }

    /**
     * Get paginated banners
     */
    public function getPaginatedBanners(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Get active banners
     */
    public function getActiveBanners(): Collection
    {
        return $this->model->active()->orderBy('created_at', 'desc')->get();
    }

    /**
     * Find banner by ID
     */
    public function findBanner(int $id): ?Banner
    {
        return $this->model->find($id);
    }

    /**
     * Create new banner
     */
    public function createBanner(array $data): Banner
    {
        return $this->model->create($data);
    }

    /**
     * Update banner
     */
    public function updateBanner(int $id, array $data): bool
    {
        $banner = $this->findBanner($id);

        if (! $banner) {
            return false;
        }

        return $banner->update($data);
    }

    /**
     * Delete banner
     */
    public function deleteBanner(int $id): bool
    {
        $banner = $this->findBanner($id);

        if (! $banner) {
            return false;
        }

        return $banner->delete();
    }

    /**
     * Get banners for DataTables
     */
    public function getForDataTables()
    {
        return $this->model->select(['id', 'banner_type', 'image', 'status', 'link', 'created_at'])
            ->orderBy('created_at', 'desc');
    }

    /**
     * Toggle banner status
     */
    public function toggleStatus(int $id): bool
    {
        $banner = $this->findBanner($id);

        if (! $banner) {
            return false;
        }

        $newStatus = $banner->status === 'active' ? 'inactive' : 'active';

        return $banner->update(['status' => $newStatus]);
    }

    /**
     * Get banner count
     */
    public function getBannerCount(): int
    {
        return $this->model->count();
    }

    /**
     * Get active banner count
     */
    public function getActiveBannerCount(): int
    {
        return $this->model->active()->count();
    }

    /**
     * Get banners by type
     */
    public function getBannersByType(string $type): Collection
    {
        return $this->model->where('banner_type', $type)
            ->active()
            ->orderBy('created_at', 'desc')
            ->get();
    }
}

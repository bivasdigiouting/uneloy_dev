<?php

namespace App\Repositories\Interfaces;

use App\Models\Banner;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface BannerRepositoryInterface
{
    /**
     * Get paginated banners
     */
    public function getPaginatedBanners(int $perPage = 15): LengthAwarePaginator;

    /**
     * Get active banners
     */
    public function getActiveBanners(): Collection;

    /**
     * Find banner by ID
     */
    public function findBanner(int $id): ?Banner;

    /**
     * Create new banner
     */
    public function createBanner(array $data): Banner;

    /**
     * Update banner
     */
    public function updateBanner(int $id, array $data): bool;

    /**
     * Delete banner
     */
    public function deleteBanner(int $id): bool;

    /**
     * Get banners for DataTables
     */
    public function getForDataTables();

    /**
     * Toggle banner status
     */
    public function toggleStatus(int $id): bool;

    /**
     * Get banner count
     */
    public function getBannerCount(): int;

    /**
     * Get active banner count
     */
    public function getActiveBannerCount(): int;

    /**
     * Get banners by type
     */
    public function getBannersByType(string $type): Collection;
}

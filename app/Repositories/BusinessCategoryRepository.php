<?php

namespace App\Repositories;

use App\Models\BusinessCategory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class BusinessCategoryRepository
{
    protected BusinessCategory $model;

    public function __construct(BusinessCategory $model)
    {
        $this->model = $model;
    }

    /**
     * Get all business categories
     */
    public function getAllCategories(): Collection
    {
        return $this->model->ordered()->get();
    }

    /**
     * Get paginated business categories
     */
    public function getPaginatedCategories(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->ordered()->paginate($perPage);
    }

    /**
     * Get active business categories
     */
    public function getActiveCategories(): Collection
    {
        return $this->model->active()->ordered()->get();
    }

    /**
     * Find business category by ID
     */
    public function findCategory(int $id): ?BusinessCategory
    {
        return $this->model->find($id);
    }

    /**
     * Find business category by slug
     */
    public function findBySlug(string $slug): ?BusinessCategory
    {
        return $this->model->where('slug', $slug)->first();
    }

    /**
     * Create new business category
     */
    public function createCategory(array $data): BusinessCategory
    {
        // Generate slug if not provided
        if (empty($data['slug']) && ! empty($data['category_name'])) {
            $data['slug'] = $this->generateUniqueSlug($data['category_name']);
        }

        return $this->model->create($data);
    }

    /**
     * Update business category
     */
    public function updateCategory(int $id, array $data): bool
    {
        $category = $this->findCategory($id);

        if (! $category) {
            return false;
        }

        // Generate new slug if category name changed
        if (! empty($data['category_name']) && $data['category_name'] !== $category->category_name) {
            if (empty($data['slug'])) {
                $data['slug'] = $this->generateUniqueSlug($data['category_name'], $id);
            }
        }

        return $category->update($data);
    }

    /**
     * Delete business category
     */
    public function deleteCategory(int $id): bool
    {
        $category = $this->findCategory($id);

        if (! $category) {
            return false;
        }

        return $category->delete();
    }

    /**
     * Search categories by name
     */
    public function searchCategories(string $search): Collection
    {
        return $this->model->where('category_name', 'LIKE', "%{$search}%")
            ->orWhere('description', 'LIKE', "%{$search}%")
            ->ordered()
            ->get();
    }

    /**
     * Get categories for DataTables
     */
    public function getForDataTables()
    {
        return $this->model->select(['id', 'category_name', 'description', 'slug', 'is_active', 'sort_order', 'created_at'])
            ->ordered();
    }

    /**
     * Toggle category status
     */
    public function toggleStatus(int $id): bool
    {
        $category = $this->findCategory($id);

        if (! $category) {
            return false;
        }

        return $category->update(['is_active' => ! $category->is_active]);
    }

    /**
     * Update sort order
     */
    public function updateSortOrder(int $id, int $sortOrder): bool
    {
        $category = $this->findCategory($id);

        if (! $category) {
            return false;
        }

        return $category->update(['sort_order' => $sortOrder]);
    }

    /**
     * Generate unique slug
     */
    private function generateUniqueSlug(string $name, ?int $excludeId = null): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while ($this->slugExists($slug, $excludeId)) {
            $slug = $originalSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Check if slug exists
     */
    private function slugExists(string $slug, ?int $excludeId = null): bool
    {
        $query = $this->model->where('slug', $slug);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Get category count
     */
    public function getCategoryCount(): int
    {
        return $this->model->count();
    }

    /**
     * Get active category count
     */
    public function getActiveCategoryCount(): int
    {
        return $this->model->active()->count();
    }
}

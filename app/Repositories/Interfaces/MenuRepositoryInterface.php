<?php

namespace App\Repositories\Interfaces;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Collection;

interface MenuRepositoryInterface
{
    /**
     * Get all menus by type
     */
    public function getMenusByType(string $type): Collection;

    /**
     * Get active menus by type
     */
    public function getActiveMenusByType(string $type): Collection;

    /**
     * Get hierarchical menu structure by type
     */
    public function getHierarchicalMenus(string $type): Collection;

    /**
     * Get all menus for DataTables
     */
    public function getForDataTables();

    /**
     * Find menu by ID
     */
    public function findMenu(int $id): ?Menu;

    /**
     * Create new menu
     */
    public function createMenu(array $data): Menu;

    /**
     * Update menu
     */
    public function updateMenu(int $id, array $data): bool;

    /**
     * Delete menu
     */
    public function deleteMenu(int $id): bool;

    /**
     * Toggle menu status
     */
    public function toggleStatus(int $id): bool;

    /**
     * Update menu order
     */
    public function updateMenuOrder(array $menuOrder): bool;

    /**
     * Get parent menus by type
     */
    public function getParentMenus(string $type): Collection;

    /**
     * Get children of a menu
     */
    public function getMenuChildren(int $parentId): Collection;

    /**
     * Get menu breadcrumb
     */
    public function getMenuBreadcrumb(int $menuId): Collection;

    /**
     * Duplicate menu with children
     */
    public function duplicateMenu(int $menuId): Menu;

    /**
     * Get max sort order for menu type
     */
    public function getMaxSortOrder(string $type, ?int $parentId = null): int;
}

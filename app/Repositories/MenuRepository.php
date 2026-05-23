<?php

namespace App\Repositories;

use App\Models\Menu;
use App\Repositories\Interfaces\MenuRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class MenuRepository implements MenuRepositoryInterface
{
    /**
     * Get all menus by type
     */
    public function getMenusByType(string $type): Collection
    {
        return Menu::byType($type)->orderBy('sort_order')->get();
    }

    /**
     * Get active menus by type
     */
    public function getActiveMenusByType(string $type): Collection
    {
        return Menu::active()->byType($type)->orderBy('sort_order')->get();
    }

    /**
     * Get hierarchical menu structure by type
     */
    public function getHierarchicalMenus(string $type): Collection
    {
        return Menu::active()
            ->byType($type)
            ->parents()
            ->with(['activeChildren' => function ($query) {
                $query->orderBy('sort_order');
            }])
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Get all menus for DataTables
     */
    public function getForDataTables()
    {
        return Menu::with('parent')
            ->select(['id', 'title', 'menu_type', 'parent_id', 'sort_order', 'is_active', 'created_at'])
            ->orderBy('menu_type')
            ->orderBy('sort_order');
    }

    /**
     * Find menu by ID
     */
    public function findMenu(int $id): ?Menu
    {
        return Menu::with(['parent', 'children'])->find($id);
    }

    /**
     * Create new menu
     */
    public function createMenu(array $data): Menu
    {
        // Set sort order if not provided
        if (! isset($data['sort_order'])) {
            $data['sort_order'] = $this->getMaxSortOrder($data['menu_type'], $data['parent_id'] ?? null) + 1;
        }

        return Menu::create($data);
    }

    /**
     * Update menu
     */
    public function updateMenu(int $id, array $data): bool
    {
        $menu = $this->findMenu($id);
        if (! $menu) {
            return false;
        }

        return $menu->update($data);
    }

    /**
     * Delete menu
     */
    public function deleteMenu(int $id): bool
    {
        $menu = $this->findMenu($id);
        if (! $menu) {
            return false;
        }

        // Delete all descendants first
        $descendants = $menu->descendants();
        foreach ($descendants as $descendant) {
            $descendant->delete();
        }

        return $menu->delete();
    }

    /**
     * Toggle menu status
     */
    public function toggleStatus(int $id): bool
    {
        $menu = $this->findMenu($id);
        if (! $menu) {
            return false;
        }

        $menu->is_active = ! $menu->is_active;

        return $menu->save();
    }

    /**
     * Update menu order
     */
    public function updateMenuOrder(array $menuOrder): bool
    {
        try {
            DB::beginTransaction();

            foreach ($menuOrder as $order => $menuData) {
                $menu = Menu::find($menuData['id']);
                if ($menu) {
                    $menu->update([
                        'sort_order' => $order + 1,
                        'parent_id' => $menuData['parent_id'] ?? null,
                    ]);

                    // Update children if they exist
                    if (isset($menuData['children']) && is_array($menuData['children'])) {
                        foreach ($menuData['children'] as $childOrder => $childData) {
                            $childMenu = Menu::find($childData['id']);
                            if ($childMenu) {
                                $childMenu->update([
                                    'sort_order' => $childOrder + 1,
                                    'parent_id' => $menu->id,
                                ]);
                            }
                        }
                    }
                }
            }

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();

            return false;
        }
    }

    /**
     * Get parent menus by type
     */
    public function getParentMenus(string $type): Collection
    {
        return Menu::byType($type)->parents()->orderBy('sort_order')->get();
    }

    /**
     * Get children of a menu
     */
    public function getMenuChildren(int $parentId): Collection
    {
        return Menu::where('parent_id', $parentId)->orderBy('sort_order')->get();
    }

    /**
     * Get menu breadcrumb
     */
    public function getMenuBreadcrumb(int $menuId): Collection
    {
        $breadcrumb = collect();
        $menu = $this->findMenu($menuId);

        while ($menu) {
            $breadcrumb->prepend($menu);
            $menu = $menu->parent;
        }

        return $breadcrumb;
    }

    /**
     * Duplicate menu with children
     */
    public function duplicateMenu(int $menuId): Menu
    {
        $originalMenu = $this->findMenu($menuId);
        if (! $originalMenu) {
            throw new \Exception('Menu not found');
        }

        $duplicatedData = $originalMenu->toArray();
        unset($duplicatedData['id'], $duplicatedData['created_at'], $duplicatedData['updated_at']);
        $duplicatedData['title'] = $duplicatedData['title'].' (Copy)';
        $duplicatedData['sort_order'] = $this->getMaxSortOrder($duplicatedData['menu_type'], $duplicatedData['parent_id']) + 1;

        $duplicatedMenu = Menu::create($duplicatedData);

        // Duplicate children
        foreach ($originalMenu->children as $child) {
            $this->duplicateChildMenu($child, $duplicatedMenu->id);
        }

        return $duplicatedMenu;
    }

    /**
     * Duplicate child menu recursively
     */
    private function duplicateChildMenu(Menu $originalChild, int $newParentId): Menu
    {
        $childData = $originalChild->toArray();
        unset($childData['id'], $childData['created_at'], $childData['updated_at']);
        $childData['parent_id'] = $newParentId;
        $childData['sort_order'] = $this->getMaxSortOrder($childData['menu_type'], $newParentId) + 1;

        $duplicatedChild = Menu::create($childData);

        // Duplicate grandchildren
        foreach ($originalChild->children as $grandchild) {
            $this->duplicateChildMenu($grandchild, $duplicatedChild->id);
        }

        return $duplicatedChild;
    }

    /**
     * Get max sort order for menu type
     */
    public function getMaxSortOrder(string $type, ?int $parentId = null): int
    {
        $query = Menu::byType($type);

        if ($parentId) {
            $query->where('parent_id', $parentId);
        } else {
            $query->whereNull('parent_id');
        }

        return $query->max('sort_order') ?? 0;
    }
}

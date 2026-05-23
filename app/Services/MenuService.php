<?php

namespace App\Services;

use App\Models\Menu;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

class MenuService
{
    /**
     * Cache duration in minutes
     */
    const CACHE_DURATION = 60;

    /**
     * Get primary menu items with hierarchical structure
     */
    public function getPrimaryMenu(): Collection
    {
        return Cache::remember('primary_menu', self::CACHE_DURATION, function () {
            return Menu::with(['activeChildren' => function ($query) {
                $query->orderBy('sort_order');
            }])
                ->active()
                ->byType('primary')
                ->parents()
                ->orderBy('sort_order')
                ->get();
        });
    }

    /**
     * Get footer menu items with hierarchical structure
     */
    public function getFooterMenu(): Collection
    {
        return Cache::remember('footer_menu', self::CACHE_DURATION, function () {
            return Menu::with(['activeChildren' => function ($query) {
                $query->orderBy('sort_order');
            }])
                ->active()
                ->byType('footer')
                ->parents()
                ->orderBy('sort_order')
                ->get();
        });
    }

    /**
     * Get all menu items by type (for admin management)
     */
    public function getMenusByType(string $type): Collection
    {
        return Menu::with(['children' => function ($query) {
            $query->orderBy('sort_order');
        }])
            ->byType($type)
            ->parents()
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Render primary navigation HTML
     */
    public function renderPrimaryNavigation(
        string $ulClass = 'navbar-nav d-flex flex-row flex-nowrap',
        string $liClass = 'nav-item',
        string $linkClass = 'nav-link'
    ): string {
        $menus = $this->getPrimaryMenu();

        return $this->buildMenuHtml($menus, $ulClass, $liClass, $linkClass, 0, 'level_1');
    }

    /**
     * Render primary menu (Alias)
     */
    public function renderPrimaryMenu(): string
    {
        return $this->renderPrimaryNavigation();
    }

    /**
     * Render footer navigation HTML
     */
    public function renderFooterNavigation(
        string $ulClass = 'footer-menu',
        string $liClass = 'footer-menu-item',
        string $linkClass = 'footer-link'
    ): string {
        $menus = $this->getFooterMenu();

        return $this->buildMenuHtml($menus, $ulClass, $liClass, $linkClass);
    }

    /**
     * Render footer menu (Alias)
     */
    public function renderFooterMenu(): string
    {
        return $this->renderFooterNavigation();
    }

    /**
     * Build menu HTML recursively
     */
    protected function buildMenuHtml(
        Collection $menus,
        string $ulClass = '',
        string $liClass = '',
        string $linkClass = '',
        int $level = 0,
        string $ulId = ''
    ): string {
        if ($menus->isEmpty()) {
            return '';
        }

        $idAttr = ($level === 0 && $ulId) ? ' id="'.$ulId.'"' : '';
        $html = '<ul'.$idAttr.' class="'.($level === 0 ? $ulClass : 'dropdown-menu').'">';

        foreach ($menus as $menu) {
            $hasChildren = $menu->activeChildren->isNotEmpty();
            $isActive = $this->isMenuActive($menu);

            // Build li classes
            $liClasses = [$liClass];
            if ($hasChildren) {
                $liClasses[] = $level === 0 ? 'dropdown' : 'dropdown-submenu';
            }
            if ($isActive) {
                $liClasses[] = 'active';
            }
            if ($menu->css_class) {
                $liClasses[] = $menu->css_class;
            }

            $html .= '<li class="'.implode(' ', array_filter($liClasses)).'">';

            // Build link
            $url = $this->getMenuUrl($menu);
            $target = $menu->open_in_new_tab ? ' target="_blank"' : '';

            // Build link classes
            $linkClasses = [$linkClass];
            if ($hasChildren) {
                $linkClasses[] = $level === 0 ? 'dropdown-toggle' : 'dropdown-item dropdown-toggle';
            } else {
                $linkClasses[] = $level > 0 ? 'dropdown-item' : '';
            }

            $linkAttributes = '';
            if ($hasChildren) {
                $linkAttributes = ' data-bs-toggle="dropdown" aria-expanded="false"';
            }

            $html .= '<a href="'.$url.'" class="'.implode(' ', array_filter($linkClasses)).'"'.$target.$linkAttributes.'>';

            // Add icon if exists
            if ($menu->icon) {
                $html .= '<i class="'.$menu->icon.' me-1"></i>';
            }

            $html .= $menu->title;

            // Add dropdown arrow for parent items
            if ($hasChildren) {
                $html .= ' <i class="ti ti-chevron-down ms-1"></i>';
            }

            $html .= '</a>';

            // Render children if exist
            if ($hasChildren) {
                $html .= $this->buildMenuHtml(
                    $menu->activeChildren,
                    $ulClass,
                    $liClass,
                    $linkClass,
                    $level + 1
                );
            }

            $html .= '</li>';
        }

        $html .= '</ul>';

        return $html;
    }

    /**
     * Get menu URL based on route or custom URL
     */
    protected function getMenuUrl(Menu $menu): string
    {
        if ($menu->route_name) {
            try {
                return route($menu->route_name);
            } catch (\Exception $e) {
                // If route doesn't exist, fall back to URL or #
                return $menu->url ?: '#';
            }
        }

        return $menu->url ?: '#';
    }

    /**
     * Check if menu item is currently active
     */
    protected function isMenuActive(Menu $menu): bool
    {
        $currentRoute = Route::currentRouteName();
        $currentUrl = request()->url();

        // Check route name match
        if ($menu->route_name && $currentRoute === $menu->route_name) {
            return true;
        }

        // Check URL match
        if ($menu->url) {
            $menuUrl = $menu->url;

            // Handle relative URLs
            if (! str_starts_with($menuUrl, 'http')) {
                $menuUrl = url($menuUrl);
            }

            if ($currentUrl === $menuUrl) {
                return true;
            }
        }

        // Check if any child is active
        foreach ($menu->activeChildren as $child) {
            if ($this->isMenuActive($child)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Generate breadcrumb from menu structure
     */
    public function generateBreadcrumb(Menu $menu): Collection
    {
        $breadcrumb = collect();
        $current = $menu;

        while ($current) {
            $breadcrumb->prepend([
                'title' => $current->title,
                'url' => $this->getMenuUrl($current),
                'is_active' => $current->id === $menu->id,
            ]);
            $current = $current->parent;
        }

        return $breadcrumb;
    }

    /**
     * Get menu tree for admin interface (with drag-drop support)
     */
    public function getMenuTree(string $type): array
    {
        $menus = Menu::with(['children' => function ($query) {
            $query->orderBy('sort_order');
        }])
            ->byType($type)
            ->parents()
            ->orderBy('sort_order')
            ->get();

        return $this->buildMenuTree($menus);
    }

    /**
     * Build menu tree array for JavaScript
     */
    protected function buildMenuTree(Collection $menus): array
    {
        $tree = [];

        foreach ($menus as $menu) {
            $item = [
                'id' => $menu->id,
                'title' => $menu->title,
                'url' => $this->getMenuUrl($menu),
                'icon' => $menu->icon,
                'is_active' => $menu->is_active,
                'sort_order' => $menu->sort_order,
                'children' => [],
            ];

            if ($menu->children->isNotEmpty()) {
                $item['children'] = $this->buildMenuTree($menu->children);
            }

            $tree[] = $item;
        }

        return $tree;
    }

    /**
     * Clear menu cache
     */
    public function clearCache(): void
    {
        Cache::forget('primary_menu');
        Cache::forget('footer_menu');
    }

    /**
     * Get menu statistics
     */
    public function getMenuStats(): array
    {
        return [
            'total_menus' => Menu::count(),
            'active_menus' => Menu::active()->count(),
            'primary_menus' => Menu::byType('primary')->count(),
            'footer_menus' => Menu::byType('footer')->count(),
            'parent_menus' => Menu::parents()->count(),
            'child_menus' => Menu::whereNotNull('parent_id')->count(),
        ];
    }

    /**
     * Render mobile navigation HTML
     */
    public function renderMobileNavigation(
        string $ulClass = 'mobile-menu',
        string $liClass = 'mobile-menu-item',
        string $linkClass = 'mobile-link'
    ): string {
        $menus = $this->getPrimaryMenu();

        return $this->buildMobileMenuHtml($menus, $ulClass, $liClass, $linkClass);
    }

    /**
     * Render mobile menu (Alias)
     */
    public function renderMobileMenu(): string
    {
        return $this->renderMobileNavigation();
    }

    /**
     * Build mobile menu HTML with collapsible structure
     */
    protected function buildMobileMenuHtml(
        Collection $menus,
        string $ulClass = '',
        string $liClass = '',
        string $linkClass = '',
        int $level = 0
    ): string {
        if ($menus->isEmpty()) {
            return '';
        }

        $html = '<ul class="'.($level === 0 ? $ulClass : 'mobile-submenu').'">';

        foreach ($menus as $menu) {
            $hasChildren = $menu->activeChildren->isNotEmpty();
            $isActive = $this->isMenuActive($menu);

            $liClasses = [$liClass];
            if ($hasChildren) {
                $liClasses[] = 'has-submenu';
            }
            if ($isActive) {
                $liClasses[] = 'active';
            }

            $html .= '<li class="'.implode(' ', array_filter($liClasses)).'">';

            $url = $this->getMenuUrl($menu);
            $target = $menu->open_in_new_tab ? ' target="_blank"' : '';

            if ($hasChildren) {
                $html .= '<a href="#" class="'.$linkClass.' submenu-toggle" data-toggle="submenu">';
            } else {
                $html .= '<a href="'.$url.'" class="'.$linkClass.'"'.$target.'>';
            }

            if ($menu->icon) {
                $html .= '<i class="'.$menu->icon.' me-2"></i>';
            }

            $html .= $menu->title;

            if ($hasChildren) {
                $html .= ' <i class="ti ti-chevron-right ms-auto submenu-arrow"></i>';
            }

            $html .= '</a>';

            if ($hasChildren) {
                $html .= $this->buildMobileMenuHtml(
                    $menu->activeChildren,
                    $ulClass,
                    $liClass,
                    $linkClass,
                    $level + 1
                );
            }

            $html .= '</li>';
        }

        $html .= '</ul>';

        return $html;
    }
}

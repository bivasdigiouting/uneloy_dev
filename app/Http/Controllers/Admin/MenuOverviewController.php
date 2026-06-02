<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuOverviewController extends Controller
{
    public function index(int $menuId): View
    {
        $menu = Menu::query()->find($menuId);

        if (! $menu) {
            abort(404);
        }

        // Overview is per head menu: show its active children
        $children = $menu->activeChildren()->orderBy('sort_order')->get();

        return view('admin.menus.overview.index', [
            'menu' => $menu,
            'children' => $children,
        ]);
    }
}


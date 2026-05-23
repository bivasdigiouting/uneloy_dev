<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Repositories\Interfaces\MenuRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class MenuController extends Controller
{
    protected $menuRepository;

    public function __construct(MenuRepositoryInterface $menuRepository)
    {
        $this->menuRepository = $menuRepository;
    }

    /**
     * Display a listing of the menus.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $menus = $this->menuRepository->getForDataTables();

            return DataTables::of($menus)
                ->addIndexColumn()
                ->addColumn('menu_type_badge', function ($menu) {
                    $badgeClass = $menu->menu_type === 'primary' ? 'bg-primary' : 'bg-info';

                    return '<span class="badge '.$badgeClass.'">'.ucfirst($menu->menu_type).'</span>';
                })
                ->addColumn('parent_name', function ($menu) {
                    return $menu->parent ? $menu->parent->title : '-';
                })
                ->addColumn('is_active_badge', function ($menu) {
                    return $menu->is_active
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                })
                ->addColumn('action', function ($menu) {
                    $actions = '<div class="btn-group" role="group">';
                    $actions .= '<a href="'.route('admin.menus.edit', $menu->id).'" class="btn btn-sm btn-primary" title="Edit"><i class="ti ti-edit"></i></a>';
                    $actions .= '<button type="button" class="btn btn-sm btn-'.($menu->is_active ? 'warning' : 'success').'" onclick="toggleMenuStatus('.$menu->id.')" title="'.($menu->is_active ? 'Deactivate' : 'Activate').'"><i class="ti ti-'.($menu->is_active ? 'eye-off' : 'eye').'"></i></button>';
                    $actions .= '<button type="button" class="btn btn-sm btn-info" onclick="duplicateMenu('.$menu->id.')" title="Duplicate"><i class="ti ti-copy"></i></button>';
                    $actions .= '<button type="button" class="btn btn-sm btn-danger" onclick="deleteMenu('.$menu->id.')" title="Delete"><i class="ti ti-trash"></i></button>';
                    $actions .= '</div>';

                    return $actions;
                })
                ->editColumn('created_at', function ($menu) {
                    return $menu->created_at->format('d M Y, h:i A');
                })
                ->rawColumns(['action', 'is_active_badge', 'menu_type_badge'])
                ->make(true);
        }

        return view('admin.menus.index');
    }

    /**
     * Show the form for creating a new menu.
     */
    public function create(): View
    {
        $parentMenus = [
            'primary' => $this->menuRepository->getParentMenus('primary'),
            'footer' => $this->menuRepository->getParentMenus('footer'),
        ];

        return view('admin.menus.create', compact('parentMenus'));
    }

    /**
     * Store a newly created menu in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'menu_type' => 'required|in:primary,footer',
            'url' => 'nullable|string|max:500',
            'route_name' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:100',
            'parent_id' => 'nullable|exists:menus,id',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'open_in_new_tab' => 'boolean',
            'css_class' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->all();
            $data['is_active'] = $request->has('is_active');
            $data['open_in_new_tab'] = $request->has('open_in_new_tab');

            $this->menuRepository->createMenu($data);

            return redirect()->route('admin.menus.index')
                ->with('success', 'Menu created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create menu: '.$e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified menu.
     */
    public function show(int $id): View
    {
        $menu = $this->menuRepository->findMenu($id);

        if (! $menu) {
            abort(404);
        }

        $breadcrumb = $this->menuRepository->getMenuBreadcrumb($id);

        return view('admin.menus.show', compact('menu', 'breadcrumb'));
    }

    /**
     * Show the form for editing the specified menu.
     */
    public function edit(int $id): View
    {
        $menu = $this->menuRepository->findMenu($id);

        if (! $menu) {
            abort(404);
        }

        $parentMenus = [
            'primary' => $this->menuRepository->getParentMenus('primary'),
            'footer' => $this->menuRepository->getParentMenus('footer'),
        ];

        return view('admin.menus.edit', compact('menu', 'parentMenus'));
    }

    /**
     * Update the specified menu in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'menu_type' => 'required|in:primary,footer',
            'url' => 'nullable|string|max:500',
            'route_name' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:100',
            'parent_id' => 'nullable|exists:menus,id',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'open_in_new_tab' => 'boolean',
            'css_class' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->all();
            $data['is_active'] = $request->has('is_active');
            $data['open_in_new_tab'] = $request->has('open_in_new_tab');

            $updated = $this->menuRepository->updateMenu($id, $data);

            if (! $updated) {
                return redirect()->back()
                    ->with('error', 'Menu not found.')
                    ->withInput();
            }

            return redirect()->route('admin.menus.index')
                ->with('success', 'Menu updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update menu: '.$e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified menu from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->menuRepository->deleteMenu($id);

            if (! $deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Menu not found.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Menu deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete menu: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Toggle menu status.
     */
    public function toggleStatus(int $id): JsonResponse
    {
        try {
            $toggled = $this->menuRepository->toggleStatus($id);

            if (! $toggled) {
                return response()->json([
                    'success' => false,
                    'message' => 'Menu not found.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Menu status updated successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update menu status: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update menu order via drag and drop.
     */
    public function updateOrder(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'menu_order' => 'required|array',
            'menu_order.*.id' => 'required|integer|exists:menus,id',
            'menu_order.*.parent_id' => 'nullable|integer|exists:menus,id',
            'menu_order.*.children' => 'nullable|array',
            'menu_order.*.children.*.id' => 'required|integer|exists:menus,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid data provided.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $updated = $this->menuRepository->updateMenuOrder($request->menu_order);

            if (! $updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update menu order.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Menu order updated successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update menu order: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Duplicate menu with children.
     */
    public function duplicate(int $id): JsonResponse
    {
        try {
            $duplicatedMenu = $this->menuRepository->duplicateMenu($id);

            return response()->json([
                'success' => true,
                'message' => 'Menu duplicated successfully.',
                'data' => $duplicatedMenu,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to duplicate menu: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get hierarchical menu structure for drag-drop interface.
     */
    public function getHierarchical(Request $request, $type = 'primary'): JsonResponse
    {
        try {
            $menus = $this->menuRepository->getHierarchicalMenus($type);

            return response()->json([
                'success' => true,
                'data' => $menus,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch menu structure: '.$e->getMessage(),
            ], 500);
        }
    }
}

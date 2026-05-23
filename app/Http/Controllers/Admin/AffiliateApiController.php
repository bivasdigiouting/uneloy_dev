<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\AffiliateApiRepositoryInterface;
use App\Repositories\AffiliateRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AffiliateApiController extends Controller
{
    public function __construct(
        private AffiliateApiRepositoryInterface $affiliateApiRepo,
        private AffiliateRepositoryInterface $affiliateRepo,
    ) {}

    public function index()
    {
        $affiliates = $this->affiliateRepo->getAllOrdered()->where('status', 'active');

        return view('admin.affiliate_apis.index', compact('affiliates'));
    }

    public function data(Request $request): JsonResponse
    {
        $query = $this->affiliateApiRepo->queryForDataTable();

        return DataTables::eloquent($query)
            ->addColumn('affiliate_name', function ($row) {
                return optional($row->affiliate)->service_name ?: '-';
            })
            ->editColumn('api_url', function ($row) {
                return '<a href="'.e($row->api_url).'" target="_blank" rel="noopener noreferrer">'.e(str($row->api_url)->limit(60)).'</a>';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at->format('d M Y, h:i A') : '-';
            })
            ->addColumn('action', function ($row) {
                $deleteUrl = route('admin.affiliate-apis.destroy', $row->id);

                return '<button class="btn btn-sm btn-danger" data-delete="'.e($deleteUrl).'">Delete</button>';
            })
            ->rawColumns(['api_url', 'action'])
            ->toJson();
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'affiliate_id' => 'required|exists:affiliates,id',
            'service' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'api_name' => 'required|string|max:255',
            'api_url' => 'required|url|max:2048',
        ]);

        $this->affiliateApiRepo->create($validated);

        return redirect()
            ->route('admin.affiliate-apis.index')
            ->with('success', 'Affiliate API created successfully.');
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->affiliateApiRepo->delete($id);

        return response()->json([
            'success' => $deleted,
            'message' => $deleted ? 'Affiliate API deleted successfully.' : 'Affiliate API not found.',
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\AffiliateLinkRepositoryInterface;
use App\Repositories\AffiliateRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AffiliateLinkController extends Controller
{
    public function __construct(
        private AffiliateLinkRepositoryInterface $affiliateLinkRepo,
        private AffiliateRepositoryInterface $affiliateRepo,
    ) {}

    public function index()
    {
        $affiliates = $this->affiliateRepo->getAllOrdered()->where('status', 'active');

        return view('admin.affiliate_links.index', compact('affiliates'));
    }

    public function data(Request $request): JsonResponse
    {
        $query = $this->affiliateLinkRepo->queryForDataTable();

        return DataTables::eloquent($query)
            ->addColumn('service_name', function ($row) {
                return optional($row->affiliate)->service_name;
            })
            ->editColumn('link_name', function ($row) {
                return $row->link_name ?: '-';
            })
            ->addColumn('tracking_url', function ($row) {
                return route('affiliate-links.redirect', ['code' => $row->code]);
            })
            ->addColumn('tracking_link', function ($row) {
                $url = route('affiliate-links.redirect', ['code' => $row->code]);

                return '<a href="'.e($url).'" target="_blank" rel="noopener noreferrer">'.e($url).'</a>';
            })
            ->addColumn('destination_link', function ($row) {
                return '<a href="'.e($row->destination_url).'" target="_blank" rel="noopener noreferrer">'.e(str($row->destination_url)->limit(60)).'</a>';
            })
            ->addColumn('active_status', function ($row) {
                return $row->isActiveOnDate()
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-danger">Inactive</span>';
            })
            ->addColumn('action', function ($row) {
                $deleteUrl = route('admin.affiliate-links.destroy', $row->id);

                return '<button class="btn btn-sm btn-danger" data-delete="'.e($deleteUrl).'">Delete</button>';
            })
            ->rawColumns(['tracking_link', 'destination_link', 'active_status', 'action'])
            ->toJson();
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'affiliate_id' => 'required|exists:affiliates,id',
            'link_name' => 'required|string|max:255',
            'destination_url' => 'required|url',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive',
        ]);

        $this->affiliateLinkRepo->createAffiliateLink([
            'affiliate_id' => $validated['affiliate_id'],
            'link_name' => $validated['link_name'],
            'destination_url' => $validated['destination_url'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'] ?? null,
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('admin.affiliate-links.index')
            ->with('success', 'Affiliate link created successfully.');
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->affiliateLinkRepo->deleteAffiliateLink($id);

        return response()->json([
            'success' => $deleted,
            'message' => $deleted ? 'Affiliate link deleted successfully.' : 'Affiliate link not found.',
        ]);
    }
}

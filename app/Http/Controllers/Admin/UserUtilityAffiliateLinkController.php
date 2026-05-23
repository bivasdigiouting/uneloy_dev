<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\CityRepositoryInterface;
use App\Repositories\Interfaces\DistrictRepositoryInterface;
use App\Repositories\Interfaces\UtilityAffiliateLinkRepositoryInterface;
use App\Repositories\StateRepositoryInterface;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UserUtilityAffiliateLinkController extends Controller
{
    public function __construct(
        private UtilityAffiliateLinkRepositoryInterface $repo,
        private StateRepositoryInterface $stateRepo,
        private DistrictRepositoryInterface $districtRepo,
        private CityRepositoryInterface $cityRepo,
    ) {}

    public function index()
    {
        $states = $this->stateRepo->getActiveStates();

        return view('admin.user_utility_affiliate_links.index', compact('states'));
    }

    public function data(Request $request)
    {
        $query = $this->repo->queryForDataTable();

        return DataTables::eloquent($query)
            ->addColumn('state_names', function ($row) {
                return $row->states->pluck('state_name')->implode(', ');
            })
            ->addColumn('district_name', function ($row) {
                return optional($row->district)->district_name;
            })
            ->addColumn('city_name', function ($row) {
                return optional($row->city)->city_name;
            })
            ->addColumn('link_short', function ($row) {
                return str($row->link)->limit(50);
            })
            ->addColumn('action', function ($row) {
                $deleteUrl = route('admin.user-utility-affiliate-links.destroy', $row->id);

                return '<button class="btn btn-sm btn-danger" data-delete="'.e($deleteUrl).'">Delete</button>';
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'audience_type' => 'required|in:User,E-Card Seva',
            'state_ids' => 'required|array|min:1',
            'state_ids.*' => 'exists:states,id',
            'district_id' => 'nullable|exists:districts,id',
            'city_id' => 'nullable|exists:cities,id',
            'type' => 'required|in:Utility & Affiliate Link',
            'from_date' => 'required|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
            'link' => 'required|string',
        ]);

        $attributes = [
            'audience_type' => $validated['audience_type'],
            'district_id' => $validated['district_id'] ?? null,
            'city_id' => $validated['city_id'] ?? null,
            'type' => $validated['type'],
            'from_date' => $validated['from_date'],
            'to_date' => $validated['to_date'] ?? null,
            'link' => $validated['link'],
        ];

        $this->repo->create($attributes, $validated['state_ids']);

        return redirect()
            ->route('admin.user-utility-affiliate-links.index')
            ->with('success', 'Utility & Affiliate Link added successfully.');
    }

    public function destroy(int $id)
    {
        $deleted = $this->repo->delete($id);
        if (request()->wantsJson()) {
            return response()->json(['success' => $deleted]);
        }

        return redirect()->back()->with($deleted ? 'success' : 'error', $deleted ? 'Deleted successfully' : 'Delete failed');
    }
}

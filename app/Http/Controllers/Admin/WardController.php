<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\CityRepositoryInterface;
use App\Repositories\Interfaces\DistrictRepositoryInterface;
use App\Repositories\MunicipalityRepositoryInterface;
use App\Repositories\StateRepositoryInterface;
use App\Repositories\WardRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class WardController extends Controller
{
    public function __construct(
        private WardRepositoryInterface $wardRepository,
        private StateRepositoryInterface $stateRepository,
        private DistrictRepositoryInterface $districtRepository,
        private CityRepositoryInterface $cityRepository,
        private MunicipalityRepositoryInterface $municipalityRepository
    ) {}

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $rows = $this->wardRepository->getForDataTables();

            return DataTables::of($rows)
                ->addIndexColumn()
                ->addColumn('state_name', fn ($row) => $row->state ? $row->state->state_name : 'N/A')
                ->addColumn('district_name', fn ($row) => $row->district ? $row->district->district_name : 'N/A')
                ->addColumn('city_name', fn ($row) => $row->city ? $row->city->city_name : 'N/A')
                ->addColumn('municipality_name', fn ($row) => $row->municipality ? $row->municipality->municipality_name : 'N/A')
                ->addColumn('status', fn ($row) => $row->status === 'active' ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>')
                ->addColumn('action', function ($row) {
                    $actions = '<div class="btn-group" role="group">';
                    $actions .= '<a href="'.route('admin.wards.edit', $row->id).'" class="btn btn-sm btn-primary" title="Edit"><i class="ti ti-edit"></i></a>';
                    $actions .= '<button type="button" class="btn btn-sm btn-warning toggle-status" data-id="'.$row->id.'" title="Toggle Status"><i class="ti ti-toggle-left"></i></button>';
                    $actions .= '<button type="button" class="btn btn-sm btn-danger delete-row" data-id="'.$row->id.'" title="Delete"><i class="ti ti-trash"></i></button>';
                    $actions .= '</div>';

                    return $actions;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.wards.index');
    }

    public function create(): View
    {
        $states = $this->stateRepository->getActiveStates();

        return view('admin.wards.create', compact('states'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'ward_no' => 'required|string|max:50',
            'state_id' => 'required|exists:states,id',
            'district_id' => 'required|exists:districts,id',
            'city_id' => 'required|exists:cities,id',
            'municipality_id' => 'required|exists:municipalities,id',
            'status' => 'required|in:active,inactive',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $this->wardRepository->create($request->all());

        return redirect()->route('admin.wards.index')->with('success', 'Ward created successfully.');
    }

    public function show(string $id): View
    {
        $row = $this->wardRepository->find((int) $id);
        abort_unless($row, 404);

        return view('admin.wards.show', compact('row'));
    }

    public function edit(string $id): View
    {
        $row = $this->wardRepository->find((int) $id);
        abort_unless($row, 404);
        $states = $this->stateRepository->getActiveStates();
        $districts = $this->districtRepository->getDistrictsByState($row->state_id);
        $cities = $this->cityRepository->getCitiesByDistrict($row->district_id);
        $municipalities = $this->municipalityRepository->getByCity($row->city_id);

        return view('admin.wards.edit', compact('row', 'states', 'districts', 'cities', 'municipalities'));
    }

    public function getWardsByMunicipality(Request $request, $municipality_id = null): JsonResponse
    {
        $municipalityId = $request->get('municipality_id') ?? $municipality_id ?? $request->route('municipality_id');
        if (! $municipalityId) {
            return response()->json(['success' => false, 'message' => 'Municipality ID is required'], 400);
        }

        $list = $this->wardRepository->getByMunicipality((int) $municipalityId);

        return response()->json(['success' => true, 'data' => $list]);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'ward_no' => 'required|string|max:50',
            'state_id' => 'required|exists:states,id',
            'district_id' => 'required|exists:districts,id',
            'city_id' => 'required|exists:cities,id',
            'municipality_id' => 'required|exists:municipalities,id',
            'status' => 'required|in:active,inactive',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $updated = $this->wardRepository->update($id, $request->all());
        abort_unless($updated, 404);

        return redirect()->route('admin.wards.index')->with('success', 'Ward updated successfully.');
    }

    public function destroy(string $id): JsonResponse
    {
        $deleted = $this->wardRepository->delete((int) $id);
        if (! $deleted) {
            return response()->json(['success' => false, 'message' => 'Ward not found'], 404);
        }

        return response()->json(['success' => true, 'message' => 'Ward deleted successfully']);
    }

    public function toggleStatus(string $id): JsonResponse
    {
        $ok = $this->wardRepository->toggleStatus((int) $id);
        if (! $ok) {
            return response()->json(['success' => false, 'message' => 'Ward not found'], 404);
        }

        return response()->json(['success' => true, 'message' => 'Ward status updated successfully']);
    }
}

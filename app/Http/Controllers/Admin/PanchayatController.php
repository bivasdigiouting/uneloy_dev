<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\CityRepositoryInterface;
use App\Repositories\Interfaces\DistrictRepositoryInterface;
use App\Repositories\PanchayatRepositoryInterface;
use App\Repositories\StateRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class PanchayatController extends Controller
{
    public function __construct(
        private PanchayatRepositoryInterface $panchayatRepository,
        private StateRepositoryInterface $stateRepository,
        private DistrictRepositoryInterface $districtRepository,
        private CityRepositoryInterface $cityRepository
    ) {}

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $rows = $this->panchayatRepository->getForDataTables();

            return DataTables::of($rows)
                ->addIndexColumn()
                ->addColumn('state_name', fn ($row) => $row->state ? $row->state->state_name : 'N/A')
                ->addColumn('district_name', fn ($row) => $row->district ? $row->district->district_name : 'N/A')
                ->addColumn('city_name', fn ($row) => $row->city ? $row->city->city_name : 'N/A')
                ->addColumn('status', fn ($row) => $row->status === 'active' ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>')
                ->addColumn('action', function ($row) {
                    $actions = '<div class="btn-group" role="group">';
                    $actions .= '<a href="'.route('admin.panchayats.edit', $row->id).'" class="btn btn-sm btn-primary" title="Edit"><i class="ti ti-edit"></i></a>';
                    $actions .= '<button type="button" class="btn btn-sm btn-warning toggle-status" data-id="'.$row->id.'" title="Toggle Status"><i class="ti ti-toggle-left"></i></button>';
                    $actions .= '<button type="button" class="btn btn-sm btn-danger delete-row" data-id="'.$row->id.'" title="Delete"><i class="ti ti-trash"></i></button>';
                    $actions .= '</div>';

                    return $actions;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.panchayats.index');
    }

    public function create(): View
    {
        $states = $this->stateRepository->getActiveStates();

        return view('admin.panchayats.create', compact('states'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'panchayat_name' => 'required|string|max:255|unique:panchayats,panchayat_name',
            'state_id' => 'required|exists:states,id',
            'district_id' => 'required|exists:districts,id',
            'city_id' => 'required|exists:cities,id',
            'status' => 'required|in:active,inactive',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $this->panchayatRepository->create($request->all());

        return redirect()->route('admin.panchayats.index')->with('success', 'Panchayat created successfully.');
    }

    public function show(string $id): View
    {
        $row = $this->panchayatRepository->find((int) $id);
        abort_unless($row, 404);

        return view('admin.panchayats.show', compact('row'));
    }

    public function edit(string $id): View
    {
        $row = $this->panchayatRepository->find((int) $id);
        abort_unless($row, 404);
        $states = $this->stateRepository->getActiveStates();
        $districts = $this->districtRepository->getDistrictsByState($row->state_id);
        $cities = $this->cityRepository->getCitiesByDistrict($row->district_id);

        return view('admin.panchayats.edit', compact('row', 'states', 'districts', 'cities'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'panchayat_name' => 'required|string|max:255|unique:panchayats,panchayat_name,'.$id,
            'state_id' => 'required|exists:states,id',
            'district_id' => 'required|exists:districts,id',
            'city_id' => 'required|exists:cities,id',
            'status' => 'required|in:active,inactive',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $updated = $this->panchayatRepository->update($id, $request->all());
        abort_unless($updated, 404);

        return redirect()->route('admin.panchayats.index')->with('success', 'Panchayat updated successfully.');
    }

    public function destroy(string $id): JsonResponse
    {
        $deleted = $this->panchayatRepository->delete((int) $id);
        if (! $deleted) {
            return response()->json(['success' => false, 'message' => 'Panchayat not found'], 404);
        }

        return response()->json(['success' => true, 'message' => 'Panchayat deleted successfully']);
    }

    public function toggleStatus(string $id): JsonResponse
    {
        $ok = $this->panchayatRepository->toggleStatus((int) $id);
        if (! $ok) {
            return response()->json(['success' => false, 'message' => 'Panchayat not found'], 404);
        }

        return response()->json(['success' => true, 'message' => 'Panchayat status updated successfully']);
    }

    public function getPanchayatsByCity(Request $request, $city_id = null): JsonResponse
    {
        $cityId = $request->get('city_id') ?? $city_id ?? $request->route('city_id');
        if (! $cityId) {
            return response()->json(['success' => false, 'message' => 'City ID is required'], 400);
        }

        $list = $this->panchayatRepository->getByCity((int) $cityId);

        return response()->json(['success' => true, 'data' => $list]);
    }
}

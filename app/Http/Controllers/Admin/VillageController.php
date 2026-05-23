<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\District;
use App\Models\State;
use App\Repositories\CityRepositoryInterface;
use App\Repositories\Interfaces\DistrictRepositoryInterface;
use App\Repositories\StateRepositoryInterface;
use App\Repositories\VillageRepositoryInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class VillageController extends Controller
{
    protected VillageRepositoryInterface $villageRepository;

    protected StateRepositoryInterface $stateRepository;

    protected DistrictRepositoryInterface $districtRepository;

    protected CityRepositoryInterface $cityRepository;

    public function __construct(
        VillageRepositoryInterface $villageRepository,
        StateRepositoryInterface $stateRepository,
        DistrictRepositoryInterface $districtRepository,
        CityRepositoryInterface $cityRepository
    ) {
        $this->villageRepository = $villageRepository;
        $this->stateRepository = $stateRepository;
        $this->districtRepository = $districtRepository;
        $this->cityRepository = $cityRepository;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $villages = $this->villageRepository->getForDataTables();

            return DataTables::of($villages)
                ->addIndexColumn()
                ->addColumn('state_name', fn ($row) => $row->state ? $row->state->state_name : 'N/A')
                ->addColumn('district_name', fn ($row) => $row->district ? $row->district->district_name : 'N/A')
                ->addColumn('city_name', fn ($row) => $row->city ? $row->city->city_name : 'N/A')
                ->addColumn('status', function ($row) {
                    return $row->status === 'active'
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                })
                ->addColumn('action', function ($row) {
                    $actions = '<div class="btn-group" role="group">';
                    $actions .= '<a href="'.route('admin.villages.show', $row->id).'" class="btn btn-sm btn-info" title="View"><i class="ti ti-eye"></i></a>';
                    $actions .= '<a href="'.route('admin.villages.edit', $row->id).'" class="btn btn-sm btn-primary" title="Edit"><i class="ti ti-edit"></i></a>';
                    $actions .= '<button type="button" class="btn btn-sm btn-warning toggle-status" data-id="'.$row->id.'" title="Toggle Status"><i class="ti ti-toggle-left"></i></button>';
                    $actions .= '<button type="button" class="btn btn-sm btn-danger delete-village" data-id="'.$row->id.'" title="Delete"><i class="ti ti-trash"></i></button>';
                    $actions .= '</div>';

                    return $actions;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.villages.index');
    }

    public function create(): View
    {
        $states = $this->stateRepository->getActiveStates();

        return view('admin.villages.create', compact('states'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'village_name' => 'required|string|max:255|unique:villages,village_name',
            'state_id' => 'required|exists:states,id',
            'district_id' => 'required|exists:districts,id',
            'city_id' => 'required|exists:cities,id',
            'status' => 'required|in:active,inactive',
        ]);

        $this->villageRepository->createVillage($request->only(['village_name', 'state_id', 'district_id', 'city_id', 'status']));

        return redirect()->route('admin.villages.index')
            ->with('success', 'Village/Town created successfully.');
    }

    public function show(string $id): View
    {
        $village = $this->villageRepository->findVillage((int) $id);
        if (! $village) {
            abort(404, 'Village not found');
        }

        return view('admin.villages.show', compact('village'));
    }

    public function edit(string $id): View
    {
        $village = $this->villageRepository->findVillage((int) $id);
        if (! $village) {
            abort(404, 'Village not found');
        }
        $states = $this->stateRepository->getActiveStates();
        $districts = $this->districtRepository->getDistrictsByState($village->state_id);
        $cities = $this->cityRepository->getCitiesByDistrict($village->district_id);

        return view('admin.villages.edit', compact('village', 'states', 'districts', 'cities'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'village_name' => 'required|string|max:255|unique:villages,village_name,'.$id,
            'state_id' => 'required|exists:states,id',
            'district_id' => 'required|exists:districts,id',
            'city_id' => 'required|exists:cities,id',
            'status' => 'required|in:active,inactive',
        ]);

        $updated = $this->villageRepository->updateVillage($id, $request->only(['village_name', 'state_id', 'district_id', 'city_id', 'status']));
        if (! $updated) {
            return redirect()->route('admin.villages.index')
                ->with('error', 'Village not found.');
        }

        return redirect()->route('admin.villages.index')
            ->with('success', 'Village/Town updated successfully.');
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $deleted = $this->villageRepository->deleteVillage((int) $id);
            if (! $deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Village not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Village/Town deleted successfully',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete village: '.$e->getMessage(),
            ], 500);
        }
    }

    public function toggleStatus(string $id): JsonResponse
    {
        try {
            $toggled = $this->villageRepository->toggleStatus((int) $id);
            if (! $toggled) {
                return response()->json([
                    'success' => false,
                    'message' => 'Village not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Village/Town status updated successfully',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update village status: '.$e->getMessage(),
            ], 500);
        }
    }

    public function getDistrictsByState(Request $request): JsonResponse
    {
        try {
            $stateId = (int) ($request->get('state_id') ?? 0);
            if (! $stateId) {
                return response()->json(['success' => false, 'message' => 'State ID is required'], 400);
            }
            $districts = $this->districtRepository->getDistrictsByState($stateId);

            return response()->json(['success' => true, 'data' => $districts]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to fetch districts: '.$e->getMessage()], 500);
        }
    }

    public function getCitiesByDistrict(Request $request): JsonResponse
    {
        try {
            $districtId = (int) ($request->get('district_id') ?? 0);
            if (! $districtId) {
                return response()->json(['success' => false, 'message' => 'District ID is required'], 400);
            }
            $cities = $this->cityRepository->getCitiesByDistrict($districtId);

            return response()->json(['success' => true, 'data' => $cities]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to fetch cities: '.$e->getMessage()], 500);
        }
    }

    public function getVillagesByCity(Request $request, $city_id = null): JsonResponse
    {
        try {
            $cityId = (int) ($request->get('city_id') ?? $city_id ?? $request->route('city_id'));
            if (! $cityId) {
                return response()->json(['success' => false, 'message' => 'City ID is required'], 400);
            }
            $villages = $this->villageRepository->getVillagesByCity($cityId);

            return response()->json(['success' => true, 'data' => $villages]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to fetch villages: '.$e->getMessage()], 500);
        }
    }

    public function bulkStore(Request $request)
    {
        $request->validate([
            'state_id' => 'required|exists:states,id',
            'district_id' => 'required|exists:districts,id',
            'city_id' => 'required|exists:cities,id',
            'names' => 'required|string',
        ]);

        $stateId = (int) $request->input('state_id');
        $districtId = (int) $request->input('district_id');
        $cityId = (int) $request->input('city_id');
        $raw = (string) $request->input('names');

        $lines = collect(preg_split('/\r?\n/', $raw))
            ->map(fn ($n) => trim((string) $n))
            ->filter(fn ($n) => $n !== '')
            ->unique(fn ($n) => mb_strtolower($n));

        $created = 0;
        $skipped = 0;
        foreach ($lines as $name) {
            $exists = $this->villageRepository->findByNameAndCity($name, $cityId);
            if ($exists) {
                $skipped++;

                continue;
            }
            $this->villageRepository->createVillage([
                'village_name' => $name,
                'state_id' => $stateId,
                'district_id' => $districtId,
                'city_id' => $cityId,
                'status' => 'active',
            ]);
            $created++;
        }

        return redirect()->route('admin.villages.index')
            ->with('success', "Bulk add completed. Created: {$created}, Skipped: {$skipped}.");
    }

    public function importCsv(Request $request): RedirectResponse
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('csv_file');
        $created = 0;
        $skipped = 0;
        $errors = 0;

        if ($file && $file->isValid()) {
            $path = $file->getRealPath();
            if (($handle = fopen($path, 'r')) !== false) {
                $header = null;
                while (($row = fgetcsv($handle, 0, ',')) !== false) {
                    if ($header === null) {
                        $header = array_map(fn ($h) => mb_strtolower(trim((string) $h)), $row);

                        continue;
                    }
                    $data = [];
                    foreach ($header as $i => $key) {
                        $data[$key] = $row[$i] ?? null;
                    }

                    $stateName = trim((string) ($data['state'] ?? ''));
                    $districtName = trim((string) ($data['district'] ?? ''));
                    $cityName = trim((string) ($data['city'] ?? ''));
                    $villageName = trim((string) ($data['village'] ?? ''));
                    $status = trim((string) ($data['status'] ?? 'active')) ?: 'active';

                    if ($villageName === '' || ($stateName === '' && empty($data['state_id'])) || ($districtName === '' && empty($data['district_id'])) || ($cityName === '' && empty($data['city_id']))) {
                        $errors++;

                        continue;
                    }

                    $stateId = (int) ($data['state_id'] ?? 0);
                    $districtId = (int) ($data['district_id'] ?? 0);
                    $cityId = (int) ($data['city_id'] ?? 0);

                    if (! $stateId) {
                        $state = State::whereRaw('LOWER(state_name) = ?', [mb_strtolower($stateName)])->first();
                        if (! $state) {
                            $state = State::create(['state_name' => $stateName, 'status' => 'active']);
                        }
                        $stateId = $state->id;
                    }

                    if (! $districtId) {
                        $district = District::where('state_id', $stateId)->whereRaw('LOWER(district_name) = ?', [mb_strtolower($districtName)])->first();
                        if (! $district) {
                            $district = District::create(['district_name' => $districtName, 'state_id' => $stateId, 'status' => 'active']);
                        }
                        $districtId = $district->id;
                    }

                    if (! $cityId) {
                        $city = City::where('state_id', $stateId)->where('district_id', $districtId)->whereRaw('LOWER(city_name) = ?', [mb_strtolower($cityName)])->first();
                        if (! $city) {
                            $city = City::create(['city_name' => $cityName, 'state_id' => $stateId, 'district_id' => $districtId, 'status' => 'active']);
                        }
                        $cityId = $city->id;
                    }

                    $exists = $this->villageRepository->findByNameAndCity($villageName, $cityId);
                    if ($exists) {
                        $skipped++;

                        continue;
                    }
                    $this->villageRepository->createVillage([
                        'village_name' => $villageName,
                        'state_id' => $stateId,
                        'district_id' => $districtId,
                        'city_id' => $cityId,
                        'status' => $status,
                    ]);
                    $created++;
                }
                fclose($handle);
            }
        }

        return redirect()->route('admin.villages.index')
            ->with('success', "CSV import completed. Created: {$created}, Skipped: {$skipped}, Errors: {$errors}.");
    }
}

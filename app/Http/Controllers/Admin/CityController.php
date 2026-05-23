<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\CityRepositoryInterface;
use App\Repositories\Interfaces\DistrictRepositoryInterface;
use App\Repositories\StateRepositoryInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class CityController extends Controller
{
    protected CityRepositoryInterface $cityRepository;

    protected StateRepositoryInterface $stateRepository;

    protected DistrictRepositoryInterface $districtRepository;

    public function __construct(
        CityRepositoryInterface $cityRepository,
        StateRepositoryInterface $stateRepository,
        DistrictRepositoryInterface $districtRepository
    ) {
        $this->cityRepository = $cityRepository;
        $this->stateRepository = $stateRepository;
        $this->districtRepository = $districtRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $cities = $this->cityRepository->getForDataTables();

            return DataTables::of($cities)
                ->addIndexColumn()
                ->addColumn('state_name', function ($city) {
                    return $city->state ? $city->state->state_name : 'N/A';
                })
                ->addColumn('district_name', function ($city) {
                    return $city->district ? $city->district->district_name : 'N/A';
                })
                ->addColumn('status', function ($city) {
                    return $city->status === 'active'
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                })
                ->addColumn('action', function ($city) {
                    $actions = '<div class="btn-group" role="group">';
                    $actions .= '<a href="'.route('admin.cities.show', $city->id).'" class="btn btn-sm btn-info" title="View"><i class="ti ti-eye"></i></a>';
                    $actions .= '<a href="'.route('admin.cities.edit', $city->id).'" class="btn btn-sm btn-primary" title="Edit"><i class="ti ti-edit"></i></a>';
                    $actions .= '<button type="button" class="btn btn-sm btn-warning toggle-status" data-id="'.$city->id.'" title="Toggle Status"><i class="ti ti-toggle-left"></i></button>';
                    $actions .= '<button type="button" class="btn btn-sm btn-danger delete-city" data-id="'.$city->id.'" title="Delete"><i class="ti ti-trash"></i></button>';
                    $actions .= '</div>';

                    return $actions;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.cities.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $states = $this->stateRepository->getActiveStates();

        return view('admin.cities.create', compact('states'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'city_name' => 'required|string|max:255|unique:cities,city_name',
                'state_id' => 'required|exists:states,id',
                'district_id' => 'required|exists:districts,id',
                'status' => 'required|in:active,inactive',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $city = $this->cityRepository->createCity($request->all());

            return response()->json([
                'success' => true,
                'message' => 'City created successfully',
                'data' => $city,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create city: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        $city = $this->cityRepository->findCity((int) $id);

        if (! $city) {
            abort(404, 'City not found');
        }

        return view('admin.cities.show', compact('city'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $city = $this->cityRepository->findCity((int) $id);

        if (! $city) {
            abort(404, 'City not found');
        }

        $states = $this->stateRepository->getActiveStates();
        $districts = $this->districtRepository->getDistrictsByState($city->state_id);

        return view('admin.cities.edit', compact('city', 'states', 'districts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'city_name' => 'required|string|max:255|unique:cities,city_name,'.$id,
                'state_id' => 'required|exists:states,id',
                'district_id' => 'required|exists:districts,id',
                'status' => 'required|in:active,inactive',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $updated = $this->cityRepository->updateCity($id, $request->all());

            if (! $updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'City not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'City updated successfully',
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update city: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $deleted = $this->cityRepository->deleteCity((int) $id);

            if (! $deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'City not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'City deleted successfully',
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete city: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Toggle city status
     */
    public function toggleStatus(string $id): JsonResponse
    {
        try {
            $toggled = $this->cityRepository->toggleStatus((int) $id);

            if (! $toggled) {
                return response()->json([
                    'success' => false,
                    'message' => 'City not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'City status updated successfully',
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update city status: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get districts by state (AJAX)
     */
    public function getDistrictsByState(Request $request): JsonResponse
    {
        try {
            $stateId = $request->get('state_id');

            if (! $stateId) {
                return response()->json([
                    'success' => false,
                    'message' => 'State ID is required',
                ], 400);
            }

            $districts = $this->districtRepository->getDistrictsByState($stateId);

            return response()->json([
                'success' => true,
                'data' => $districts,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch districts: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get cities by district (AJAX)
     */
    public function getCitiesByDistrict(Request $request, $district_id = null): JsonResponse
    {
        try {
            $districtId = $request->get('district_id') ?? $district_id ?? $request->route('district_id');

            if (! $districtId) {
                return response()->json([
                    'success' => false,
                    'message' => 'District ID is required',
                ], 400);
            }

            $cities = $this->cityRepository->getCitiesByDistrict((int) $districtId);

            return response()->json([
                'success' => true,
                'data' => $cities,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch cities: '.$e->getMessage(),
            ], 500);
        }
    }
}

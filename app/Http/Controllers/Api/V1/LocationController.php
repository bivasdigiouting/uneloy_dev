<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Repositories\CityRepositoryInterface;
use App\Repositories\Interfaces\DistrictRepositoryInterface;
use App\Repositories\StateRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    protected StateRepositoryInterface $stateRepository;

    protected DistrictRepositoryInterface $districtRepository;

    protected CityRepositoryInterface $cityRepository;

    public function __construct(
        StateRepositoryInterface $stateRepository,
        DistrictRepositoryInterface $districtRepository,
        CityRepositoryInterface $cityRepository
    ) {
        $this->stateRepository = $stateRepository;
        $this->districtRepository = $districtRepository;
        $this->cityRepository = $cityRepository;
    }

    /**
     * Get all active states
     *
     * @group Location
     *
     * @unauthenticated
     *
     * @response 200 {"success":true,"data":[{"id":1,"state_name":"Gujarat"}]}
     */
    public function states(): JsonResponse
    {
        try {
            $states = $this->stateRepository->getActiveStates()
                ->map(fn ($s) => [
                    'id' => $s->id,
                    'state_name' => $s->state_name,
                ]);

            return response()->json([
                'success' => true,
                'data' => $states,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch states',
            ], 500);
        }
    }

    /**
     * Get all active districts by state
     *
     * @group Location
     *
     * @unauthenticated
     *
     * @queryParam state_id integer required The state ID. Example: 5
     *
     * @response 200 {"success":true,"data":[{"id":1,"district_name":"Ahmedabad"}]}
     * @response 400 {"success":false,"message":"State ID is required"}
     */
    public function districts(Request $request): JsonResponse
    {
        try {
            $stateId = (int) $request->input('state_id');
            if (! $stateId) {
                return response()->json([
                    'success' => false,
                    'message' => 'State ID is required',
                ], 400);
            }

            $districts = $this->districtRepository->getDistrictsByState($stateId)
                ->map(fn ($d) => [
                    'id' => $d->id,
                    'district_name' => $d->district_name,
                ]);

            return response()->json([
                'success' => true,
                'data' => $districts,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch districts',
            ], 500);
        }
    }

    /**
     * Get all active cities by state and district
     *
     * @group Location
     *
     * @unauthenticated
     *
     * @queryParam state_id integer required The state ID. Example: 5
     * @queryParam district_id integer required The district ID. Example: 12
     *
     * @response 200 {"success":true,"data":[{"id":1,"city_name":"Ahmedabad"}]}
     * @response 400 {"success":false,"message":"State ID and District ID are required"}
     */
    public function cities(Request $request): JsonResponse
    {
        try {
            $stateId = (int) $request->input('state_id');
            $districtId = (int) $request->input('district_id');
            if (! $stateId || ! $districtId) {
                return response()->json([
                    'success' => false,
                    'message' => 'State ID and District ID are required',
                ], 400);
            }

            $cities = $this->cityRepository->getCitiesByStateAndDistrict($stateId, $districtId)
                ->map(fn ($c) => [
                    'id' => $c->id,
                    'city_name' => $c->city_name,
                ]);

            return response()->json([
                'success' => true,
                'data' => $cities,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch cities',
            ], 500);
        }
    }
}

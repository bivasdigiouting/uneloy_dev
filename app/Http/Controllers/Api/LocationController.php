<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\District;
use App\Models\Municipality;
use App\Models\Panchayat;
use App\Models\State;
use App\Models\Village;
use App\Models\Ward;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Get all active states
     */
    public function getStates(): JsonResponse
    {
        try {
            $states = State::active()
                ->ordered()
                ->select('id', 'state_name')
                ->get();

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
     * Get districts by state ID
     */
    public function getDistrictsByState(Request $request): JsonResponse
    {
        try {
            $stateId = $request->input('state_id');

            if (! $stateId) {
                return response()->json([
                    'success' => false,
                    'message' => 'State ID is required',
                ], 400);
            }

            $districts = District::active()
                ->where('state_id', $stateId)
                ->ordered()
                ->select('id', 'district_name')
                ->get();

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
     * Get cities by district ID
     */
    public function getCitiesByDistrict(Request $request): JsonResponse
    {
        try {
            $districtId = $request->input('district_id');

            if (! $districtId) {
                return response()->json([
                    'success' => false,
                    'message' => 'District ID is required',
                ], 400);
            }

            $cities = City::active()
                ->where('district_id', $districtId)
                ->ordered()
                ->select('id', 'city_name')
                ->get();

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

    /**
     * Get cities by state ID (for direct state to city mapping)
     */
    public function getCitiesByState(Request $request): JsonResponse
    {
        try {
            $stateId = $request->input('state_id');

            if (! $stateId) {
                return response()->json([
                    'success' => false,
                    'message' => 'State ID is required',
                ], 400);
            }

            $cities = City::active()
                ->where('state_id', $stateId)
                ->ordered()
                ->select('id', 'city_name')
                ->get();

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

    public function getPanchayatsByCity(Request $request): JsonResponse
    {
        try {
            $cityId = $request->input('city_id');
            if (! $cityId) {
                return response()->json([
                    'success' => false,
                    'message' => 'City ID is required',
                ], 400);
            }

            $items = Panchayat::active()
                ->where('city_id', $cityId)
                ->ordered()
                ->select('id', 'panchayat_name')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $items,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch panchayats',
            ], 500);
        }
    }

    public function getMunicipalitiesByCity(Request $request): JsonResponse
    {
        try {
            $cityId = $request->input('city_id');
            if (! $cityId) {
                return response()->json([
                    'success' => false,
                    'message' => 'City ID is required',
                ], 400);
            }

            $items = Municipality::active()
                ->where('city_id', $cityId)
                ->ordered()
                ->select('id', 'municipality_name')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $items,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch municipalities',
            ], 500);
        }
    }

    public function getVillagesByCity(Request $request): JsonResponse
    {
        try {
            $cityId = $request->input('city_id');
            if (! $cityId) {
                return response()->json([
                    'success' => false,
                    'message' => 'City ID is required',
                ], 400);
            }

            $items = Village::active()
                ->where('city_id', $cityId)
                ->ordered()
                ->select('id', 'village_name')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $items,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch villages',
            ], 500);
        }
    }

    public function getWardsByMunicipality(Request $request): JsonResponse
    {
        try {
            $municipalityId = $request->input('municipality_id');
            if (! $municipalityId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Municipality ID is required',
                ], 400);
            }

            $items = Ward::active()
                ->where('municipality_id', $municipalityId)
                ->ordered()
                ->select('id', 'ward_no')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $items,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch wards',
            ], 500);
        }
    }
}

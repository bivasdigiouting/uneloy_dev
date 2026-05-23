<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\District;
use App\Models\State;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class IndianCitiesSeeder extends Seeder
{
    public function run(): void
    {
        // Map of state name -> id
        $stateIdByName = State::query()->pluck('id', 'state_name')->mapWithKeys(function ($id, $name) {
            return [self::normalize($name) => $id];
        });

        // Map of [state_id][normalized_district_name] -> district_id
        $districtIdByStateAndName = [];
        District::query()->get(['id', 'district_name', 'state_id'])->each(function ($d) use (&$districtIdByStateAndName) {
            $districtIdByStateAndName[$d->state_id][self::normalize($d->district_name)] = $d->id;
        });

        // Load hierarchical dataset: State -> Districts -> Cities
        $data = $this->loadCitiesData();
        $created = 0;
        $skipped = 0;

        foreach ($data as $stateName => $districts) {
            $normState = self::normalize($stateName);
            $stateId = $stateIdByName[$normState] ?? null;
            if (! $stateId) {
                Log::warning("IndianCitiesSeeder: Unknown state '$stateName' — skipping.");

                continue;
            }

            foreach ($districts as $districtName => $cities) {
                $normDistrict = self::normalize($districtName);
                $districtId = $districtIdByStateAndName[$stateId][$normDistrict] ?? null;
                if (! $districtId) {
                    Log::warning("IndianCitiesSeeder: Unknown district '$districtName' in state '$stateName' — skipping its cities.");
                    $skipped += is_array($cities) ? count($cities) : 1;

                    continue;
                }

                foreach ((array) $cities as $cityName) {
                    $cityName = trim((string) $cityName);
                    if ($cityName === '') {
                        $skipped++;

                        continue;
                    }
                    City::firstOrCreate(
                        [
                            'city_name' => $cityName,
                            'state_id' => $stateId,
                            'district_id' => $districtId,
                        ],
                        [
                            'status' => 'active',
                        ]
                    );
                    $created++;
                }
            }
        }

        $this->command->info("IndianCitiesSeeder: Seeded $created cities. Skipped $skipped entries due to missing mappings.");
    }

    /**
     * Attempt to load hierarchical cities data from local file or remote.
     * Expected shape:
     * {
     *   "Gujarat": {
     *     "Ahmedabad": ["Ahmedabad", "Dholka", "Dhandhuka"],
     *     "Surat": ["Surat", "Bardoli", "Vyara"],
     *   },
     *   "Maharashtra": { ... }
     * }
     */
    private function loadCitiesData(): array
    {
        $local = database_path('data/india-cities-by-district.json');
        $localData = null;
        if (File::exists($local)) {
            try {
                $json = File::get($local);
                $decoded = json_decode($json, true);
                if (is_array($decoded)) {
                    $localData = $decoded;
                }
            } catch (\Throwable $e) {
            }
        }

        $fallback = [];
        $states = State::query()->get(['id', 'state_name']);
        foreach ($states as $state) {
            $districts = District::query()->where('state_id', $state->id)->get(['district_name']);
            if ($districts->isEmpty()) {
                continue;
            }
            $fallback[$state->state_name] = [];
            foreach ($districts as $d) {
                $fallback[$state->state_name][$d->district_name] = [$d->district_name];
            }
        }

        if (! $localData) {
            return $fallback;
        }

        foreach ($fallback as $stateName => $dMap) {
            if (! isset($localData[$stateName]) || ! is_array($localData[$stateName])) {
                $localData[$stateName] = $dMap;

                continue;
            }
            foreach ($dMap as $districtName => $cities) {
                if (! isset($localData[$stateName][$districtName]) || ! is_array($localData[$stateName][$districtName]) || count($localData[$stateName][$districtName]) === 0) {
                    $localData[$stateName][$districtName] = $cities;
                }
            }
        }

        return $localData;
    }

    private static function normalize(string $name): string
    {
        $n = strtolower(trim($name));
        $n = str_replace(['&', ' and ', '-'], ' ', $n);
        $n = preg_replace('/\s+/', ' ', $n);

        return $n;
    }
}

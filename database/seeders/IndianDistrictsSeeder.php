<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\State;
use Illuminate\Database\Seeder;

class IndianDistrictsSeeder extends Seeder
{
    /**
     * Seed the districts table with all Indian districts linked to states.
     */
    public function run(): void
    {
        $data = $this->loadDataset();

        if (! $data || ! isset($data['states']) || ! is_array($data['states'])) {
            $this->command->warn('IndianDistrictsSeeder: dataset not found or invalid. Skipping.');

            return;
        }

        // Build a map of state_name => id for faster lookups
        $stateMap = State::query()->pluck('id', 'state_name')->toArray();

        $created = 0;
        foreach ($data['states'] as $entry) {
            $stateName = trim($entry['state'] ?? '');
            $districts = $entry['districts'] ?? [];

            if ($stateName === '' || ! isset($stateMap[$stateName])) {
                // Try a couple of common name normalizations
                $normalized = $this->normalizeStateName($stateName);
                if ($normalized && isset($stateMap[$normalized])) {
                    $stateName = $normalized;
                } else {
                    $this->command->warn("Skipping state not found in DB: {$stateName}");

                    continue;
                }
            }

            $stateId = $stateMap[$stateName];

            foreach ($districts as $name) {
                $dName = trim((string) $name);
                if ($dName === '') {
                    continue;
                }

                // Avoid duplicate by unique pair (state_id, district_name)
                District::firstOrCreate(
                    [
                        'district_name' => $dName,
                        'state_id' => $stateId,
                    ],
                    [
                        'status' => 'active',
                    ]
                );
                $created++;
            }
        }

        $this->command->info("IndianDistrictsSeeder: ensured {$created} district records.");
    }

    /**
     * Try to load dataset from local file, falling back to GitHub raw JSON if available.
     */
    protected function loadDataset(): ?array
    {
        // Preferred local path (you can replace with your own path if needed)
        $localPath = base_path('database/seeders/data/states-and-districts.json');

        $json = null;
        if (file_exists($localPath)) {
            $json = @file_get_contents($localPath);
        }

        if (! $json) {
            // Fallback: public dataset on GitHub
            $url = 'https://raw.githubusercontent.com/sab99r/Indian-States-And-Districts/master/states-and-districts.json';
            try {
                $json = @file_get_contents($url);
            } catch (\Throwable $e) {
                // ignore
            }
        }

        if (! $json) {
            return null;
        }

        $data = json_decode($json, true);

        return is_array($data) ? $data : null;
    }

    /**
     * Normalize common state/UT name variants to match our seeded names.
     */
    protected function normalizeStateName(string $name): ?string
    {
        $map = [
            'National Capital Territory of Delhi' => 'Delhi',
            'NCT of Delhi' => 'Delhi',
            'Andaman & Nicobar Islands' => 'Andaman and Nicobar Islands',
            'Pondicherry' => 'Puducherry',
        ];

        $trimmed = trim($name);

        return $map[$trimmed] ?? $trimmed;
    }
}

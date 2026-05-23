<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Seeder;

class IndianStatesSeeder extends Seeder
{
    /**
     * Seed the states table with all Indian states and union territories.
     */
    public function run(): void
    {
        $states = [
            // 28 States
            'Andhra Pradesh',
            'Arunachal Pradesh',
            'Assam',
            'Bihar',
            'Chhattisgarh',
            'Goa',
            'Gujarat',
            'Haryana',
            'Himachal Pradesh',
            'Jharkhand',
            'Karnataka',
            'Kerala',
            'Madhya Pradesh',
            'Maharashtra',
            'Manipur',
            'Meghalaya',
            'Mizoram',
            'Nagaland',
            'Odisha',
            'Punjab',
            'Rajasthan',
            'Sikkim',
            'Tamil Nadu',
            'Telangana',
            'Tripura',
            'Uttar Pradesh',
            'Uttarakhand',
            'West Bengal',
            // 8 Union Territories
            'Andaman and Nicobar Islands',
            'Chandigarh',
            'Dadra and Nagar Haveli and Daman and Diu',
            'Delhi',
            'Jammu and Kashmir',
            'Ladakh',
            'Lakshadweep',
            'Puducherry',
        ];

        foreach ($states as $name) {
            State::firstOrCreate(
                ['state_name' => $name],
                ['status' => 'active']
            );
        }
    }
}

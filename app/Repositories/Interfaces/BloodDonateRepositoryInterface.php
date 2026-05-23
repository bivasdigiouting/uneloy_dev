<?php

namespace App\Repositories\Interfaces;

interface BloodDonateRepositoryInterface
{
    /**
     * Get the Blood Donate data.
     *
     * @return \App\Models\BloodDonate|null
     */
    public function getBloodDonate();

    /**
     * Update or create the Blood Donate data.
     *
     * @param array $data
     * @return \App\Models\BloodDonate
     */
    public function update(array $data);
}

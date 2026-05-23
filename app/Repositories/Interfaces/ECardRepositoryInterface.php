<?php

namespace App\Repositories\Interfaces;

interface ECardRepositoryInterface
{
    /**
     * Get the E-Card service data.
     *
     * @return \App\Models\ECardService|null
     */
    public function getECardService();

    /**
     * Update or create the E-Card service data.
     *
     * @param array $data
     * @return \App\Models\ECardService
     */
    public function update(array $data);
}

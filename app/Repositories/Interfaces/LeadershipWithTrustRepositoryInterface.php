<?php

namespace App\Repositories\Interfaces;

interface LeadershipWithTrustRepositoryInterface
{
    public function updateLeadershipWithTrust(array $data);
    public function getLeadershipWithTrust();
}

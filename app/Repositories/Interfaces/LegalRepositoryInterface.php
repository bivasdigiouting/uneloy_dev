<?php

namespace App\Repositories\Interfaces;

interface LegalRepositoryInterface
{
    public function updateLegal(array $data);
    public function getLegal();
}

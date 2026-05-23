<?php

namespace App\Repositories\Interfaces;

interface EcardSevaProductCommissionRepositoryInterface
{
    public function all();
    public function getForDataTable();
    public function find($id);
    public function findByInhouseProductId($inhouseProductId);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function toggleStatus($id);
    public function existsForInhouseProduct($inhouseProductId, $excludeId = null);
}

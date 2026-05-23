<?php

namespace App\Repositories\Interfaces;

interface ProductCategoryRepositoryInterface
{
    public function all();

    public function find($id);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);

    public function getActive();

    public function getInactive();

    public function updateStatus($id, $status);

    public function getForDataTable();

    public function getTotalCount();

    public function getActiveCount();

    public function getInactiveCount();
}

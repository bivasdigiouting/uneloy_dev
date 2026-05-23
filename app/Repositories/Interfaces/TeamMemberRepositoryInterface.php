<?php

namespace App\Repositories\Interfaces;

interface TeamMemberRepositoryInterface
{
    public function getAll();
    public function getActive();
    public function getById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function toggleStatus($id);
}

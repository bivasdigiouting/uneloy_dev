<?php

namespace App\Repositories\Interfaces;

interface FaqRepositoryInterface
{
    public function getAllFaqs();
    public function getActiveFaqs();
    public function getFaqById($id);
    public function createFaq(array $data);
    public function updateFaq($id, array $data);
    public function deleteFaq($id);
    public function toggleStatus($id);
}

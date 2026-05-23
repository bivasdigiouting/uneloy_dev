<?php

namespace App\Repositories;

use App\Models\WebsiteBenefit;
use App\Repositories\Interfaces\WebsiteBenefitRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class WebsiteBenefitRepository implements WebsiteBenefitRepositoryInterface
{
    protected $model;

    public function __construct(WebsiteBenefit $model)
    {
        $this->model = $model;
    }

    /**
     * Get all website benefits
     */
    public function getAll()
    {
        return $this->model->orderBy('sequence', 'asc')->get();
    }

    /**
     * Get active website benefits ordered by sequence
     */
    public function getActive()
    {
        return $this->model->active()->ordered()->get();
    }

    /**
     * Find website benefit by ID
     */
    public function findById($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create new website benefit
     */
    public function create(array $data)
    {
        // Handle icon upload
        if (isset($data['icon']) && $data['icon']) {
            $data['icon'] = $this->uploadIcon($data['icon']);
        }

        return $this->model->create($data);
    }

    /**
     * Update website benefit
     */
    public function update($id, array $data)
    {
        $benefit = $this->findById($id);

        // Handle icon upload
        if (isset($data['icon']) && $data['icon']) {
            // Delete old icon if exists
            if ($benefit->icon && Storage::disk('public')->exists($benefit->icon)) {
                Storage::disk('public')->delete($benefit->icon);
            }
            $data['icon'] = $this->uploadIcon($data['icon']);
        }

        $benefit->update($data);

        return $benefit;
    }

    /**
     * Delete website benefit
     */
    public function delete($id)
    {
        $benefit = $this->findById($id);

        // Delete icon if exists
        if ($benefit->icon && Storage::disk('public')->exists($benefit->icon)) {
            Storage::disk('public')->delete($benefit->icon);
        }

        return $benefit->delete();
    }

    /**
     * Get website benefits for DataTables
     */
    public function getForDataTable()
    {
        return $this->model->select(['id', 'benefit_name', 'sequence', 'icon', 'is_active', 'created_at'])
            ->orderBy('sequence', 'asc');
    }

    /**
     * Update sequence for multiple benefits
     */
    public function updateSequences(array $sequences)
    {
        foreach ($sequences as $id => $sequence) {
            $this->model->where('id', $id)->update(['sequence' => $sequence]);
        }
    }

    /**
     * Upload icon file
     */
    private function uploadIcon($file)
    {
        return $file->store('website-benefits/icons', 'public');
    }
}

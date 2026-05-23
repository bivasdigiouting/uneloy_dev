<?php

namespace App\Repositories;

use App\Models\VendorType;
use App\Repositories\Interfaces\VendorTypeRepositoryInterface;
use Yajra\DataTables\Facades\DataTables;

class VendorTypeRepository implements VendorTypeRepositoryInterface
{
    protected $model;

    public function __construct(VendorType $model)
    {
        $this->model = $model;
    }

    /**
     * Get all vendor types
     */
    public function getAll()
    {
        return $this->model->orderBy('vendor_type', 'asc')->get();
    }

    /**
     * Get vendor type by ID
     */
    public function findById($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create a new vendor type
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Update vendor type
     */
    public function update($id, array $data)
    {
        $vendorType = $this->findById($id);
        $vendorType->update($data);

        return $vendorType;
    }

    /**
     * Delete vendor type
     */
    public function delete($id)
    {
        $vendorType = $this->findById($id);

        return $vendorType->delete();
    }

    /**
     * Get active vendor types
     */
    public function getActive()
    {
        return $this->model->active()->orderBy('vendor_type', 'asc')->get();
    }

    /**
     * Toggle vendor type status
     */
    public function toggleStatus($id)
    {
        $vendorType = $this->findById($id);
        $vendorType->is_active = ! $vendorType->is_active;
        $vendorType->save();

        return $vendorType;
    }

    /**
     * Get vendor types for DataTables
     */
    public function getForDataTables()
    {
        $vendorTypes = $this->model->select(['id', 'vendor_type', 'is_active', 'created_at']);

        return DataTables::of($vendorTypes)
            ->addIndexColumn()
            ->addColumn('status', function ($row) {
                $statusClass = $row->is_active ? 'success' : 'danger';
                $statusText = $row->is_active ? 'Active' : 'Inactive';

                return '<span class="badge badge-'.$statusClass.'">'.$statusText.'</span>';
            })
            ->addColumn('action', function ($row) {
                $editBtn = '<a href="'.route('admin.vendor-types.edit', $row->id).'" class="btn btn-sm btn-primary me-1">
                    <i class="fas fa-edit"></i> Edit
                </a>';

                $deleteBtn = '<button type="button" class="btn btn-sm btn-danger me-1" onclick="deleteVendorType('.$row->id.')">
                    <i class="fas fa-trash"></i> Delete
                </button>';

                $statusBtn = $row->is_active
                    ? '<button type="button" class="btn btn-sm btn-warning" onclick="toggleStatus('.$row->id.')">
                        <i class="fas fa-eye-slash"></i> Deactivate
                    </button>'
                    : '<button type="button" class="btn btn-sm btn-success" onclick="toggleStatus('.$row->id.')">
                        <i class="fas fa-eye"></i> Activate
                    </button>';

                return $editBtn.$deleteBtn.$statusBtn;
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('d M Y, h:i A');
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    /**
     * Check if vendor type name exists
     */
    public function existsByName($name, $excludeId = null)
    {
        $query = $this->model->where('vendor_type', $name);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Get vendor types count by status
     */
    public function getCountByStatus($status = null)
    {
        if ($status === null) {
            return $this->model->count();
        }

        return $this->model->where('is_active', $status)->count();
    }
}

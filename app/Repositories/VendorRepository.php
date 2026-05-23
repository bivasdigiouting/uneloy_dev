<?php

namespace App\Repositories;

use App\Models\Vendor;
use App\Repositories\Interfaces\VendorRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;
use Yajra\DataTables\Facades\DataTables;

class VendorRepository implements VendorRepositoryInterface
{
    /**
     * Get paginated vendors
     */
    public function getPaginatedVendors(int $perPage = 15): LengthAwarePaginator
    {
        return Vendor::orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Get active vendors
     */
    public function getActiveVendors(): Collection
    {
        $q = Vendor::active();
        if (Schema::hasColumn('vendors', 'vendor_name')) {
            $q->orderBy('vendor_name');
        } elseif (Schema::hasColumn('vendors', 'business_name')) {
            $q->orderBy('business_name');
        } else {
            $q->orderBy('created_at', 'desc');
        }

        return $q->get();
    }

    /**
     * Find vendor by ID
     */
    public function findVendor(int $id): ?Vendor
    {
        return Vendor::find($id);
    }

    /**
     * Create new vendor
     */
    public function createVendor(array $data): Vendor
    {
        return Vendor::create($data);
    }

    /**
     * Update vendor
     */
    public function updateVendor(int $id, array $data): bool
    {
        $vendor = $this->findVendor($id);
        if (! $vendor) {
            return false;
        }

        return $vendor->update($data);
    }

    /**
     * Delete vendor
     */
    public function deleteVendor(int $id): bool
    {
        $vendor = $this->findVendor($id);
        if (! $vendor) {
            return false;
        }

        return $vendor->delete();
    }

    /**
     * Get vendors for DataTables
     */
    public function getForDataTables()
    {
        return Vendor::select([
            'id',
            'business_name',
            'contact_person',
            'mobile_no',
            'gmail_id',
            'business_registration_category',
            'status',
            'created_at',
        ])->with(['state', 'district', 'city']);
    }

    /**
     * Toggle vendor status
     */
    public function toggleStatus(int $id): bool
    {
        $vendor = $this->findVendor($id);
        if (! $vendor) {
            return false;
        }

        $newStatus = $vendor->status === 'active' ? 'inactive' : 'active';

        return $vendor->update(['status' => $newStatus]);
    }

    /**
     * Get vendor count
     */
    public function getVendorCount(): int
    {
        return Vendor::count();
    }

    /**
     * Get active vendor count
     */
    public function getActiveVendorCount(): int
    {
        return Vendor::active()->count();
    }

    /**
     * Get inactive vendor count
     */
    public function getInactiveVendorCount(): int
    {
        return Vendor::inactive()->count();
    }

    /**
     * Get vendors by status
     */
    public function getVendorsByStatus(string $status): Collection
    {
        $q = Vendor::where('status', $status);
        if (Schema::hasColumn('vendors', 'vendor_name')) {
            $q->orderBy('vendor_name');
        } elseif (Schema::hasColumn('vendors', 'business_name')) {
            $q->orderBy('business_name');
        } else {
            $q->orderBy('created_at', 'desc');
        }

        return $q->get();
    }
}

<?php

namespace App\Repositories;

use App\Models\Notification;
use App\Repositories\Interfaces\NotificationRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class NotificationRepository implements NotificationRepositoryInterface
{
    /**
     * Base query for DataTables with relations.
     */
    public function getForDataTable(): Builder
    {
        return Notification::with(['state', 'district', 'city'])->ordered();
    }

    /**
     * Create a single notification.
     */
    public function create(array $data): Notification
    {
        return Notification::create($data);
    }

    /**
     * Bulk create notifications for multiple state IDs.
     * If stateIds is empty, creates one record with state_id = null.
     */
    public function bulkCreateForStates(array $baseData, array $stateIds): array
    {
        $createdIds = [];

        if (empty($stateIds)) {
            $notification = Notification::create(array_merge($baseData, [
                'state_id' => null,
            ]));
            $createdIds[] = $notification->id;

            return $createdIds;
        }

        foreach ($stateIds as $stateId) {
            $notification = Notification::create(array_merge($baseData, [
                'state_id' => $stateId,
            ]));
            $createdIds[] = $notification->id;
        }

        return $createdIds;
    }

    /**
     * Delete a notification and its stored image if present.
     */
    public function delete(int $id): bool
    {
        $notification = Notification::find($id);
        if (! $notification) {
            return false;
        }

        if ($notification->image && Storage::disk('public')->exists($notification->image)) {
            Storage::disk('public')->delete($notification->image);
        }

        return (bool) $notification->delete();
    }

    /**
     * Find a notification by ID.
     */
    public function find(int $id): ?Notification
    {
        return Notification::find($id);
    }
}

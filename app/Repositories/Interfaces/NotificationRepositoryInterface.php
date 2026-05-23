<?php

namespace App\Repositories\Interfaces;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Builder;

interface NotificationRepositoryInterface
{
    /**
     * Get base query for DataTables.
     */
    public function getForDataTable(): Builder;

    /**
     * Create a single notification.
     */
    public function create(array $data): Notification;

    /**
     * Bulk create notifications for multiple state IDs.
     * Returns created notification IDs in order.
     */
    public function bulkCreateForStates(array $baseData, array $stateIds): array;

    /**
     * Delete a notification by ID.
     */
    public function delete(int $id): bool;

    /**
     * Find a notification by ID.
     */
    public function find(int $id): ?Notification;
}

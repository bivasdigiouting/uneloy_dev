<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'expense_name',
        'description',
        'amount',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'amount' => 'decimal:2',
    ];

    /**
     * Get active expenses only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Check if expense can be deleted
     */
    public function canBeDeleted(): bool
    {
        // Add logic here if expense has related records
        return true;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ECardSevaEmergencyOtherPoint extends Model
{
    use HasFactory;

    protected $table = 'ecard_seva_emergency_other_points';

    protected $fillable = [
        'points',
        // Approved info
        'approved_id_no',
        'approved_name',
        'approved_date',
        // User info
        'name',
        'mobile_no',
        'emergency_type',
        'age',
        'gender',
        'live_location',
        'description',
        // Request info
        'request_date',
        'image',
        'status',
        // Send points info
        'send_points_remarks',
        'send_points_date',
    ];

    protected $casts = [
        'approved_date' => 'datetime',
        'request_date' => 'datetime',
        'send_points_date' => 'datetime',
        'age' => 'integer',
        'points' => 'integer',
    ];

    protected $appends = [
        'approved_composite',
    ];

    public function getApprovedCompositeAttribute(): string
    {
        $id = trim((string) ($this->approved_id_no ?? ''));
        $name = trim((string) ($this->approved_name ?? ''));
        $parts = [];
        if ($id !== '') {
            $parts[] = $id;
        }
        if ($name !== '') {
            $parts[] = $name;
        }

        return implode(', ', $parts);
    }
}

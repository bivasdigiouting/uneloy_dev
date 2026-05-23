<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodDonateOtherPoint extends Model
{
    use HasFactory;

    protected $table = 'blood_donate_other_points';

    protected $fillable = [
        'points',
        'approved_id_no',
        'approved_name',
        'approved_date',
        'name',
        'mobile_no',
        'age',
        'gender',
        'blood_group',
        'hospital_name',
        'hospital_address',
        'request_date',
        'status',
        'proof_document',
        'upload_proof_document',
        'proof_remarks',
        'send_points',
        'send_points_remarks',
        'send_points_date',
    ];

    protected $casts = [
        'approved_date' => 'datetime',
        'request_date' => 'datetime',
        'send_points_date' => 'datetime',
        'points' => 'integer',
        'send_points' => 'integer',
    ];

    public function getApprovedCompositeAttribute(): string
    {
        $id = trim((string) ($this->approved_id_no ?? ''));
        $name = trim((string) ($this->approved_name ?? ''));

        return trim($id.($id && $name ? ', ' : '').$name);
    }
}

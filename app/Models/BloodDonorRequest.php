<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodDonorRequest extends Model
{
    protected $fillable = [
        'requester_user_id',
        'requester_name',
        'requester_mobile_no',
        'donor_id',
        'donor_name',
        'donor_mobile_no',
        'blood_group',
        'status',
        'notes',
    ];

    public function messages()
    {
        return $this->hasMany(BloodDonorMessage::class);
    }
}

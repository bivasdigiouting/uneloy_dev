<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodDonorMessage extends Model
{
    protected $fillable = [
        'blood_donor_request_id',
        'sender_user_id',
        'sender_name',
        'sender_mobile_no',
        'message',
    ];

    public function request()
    {
        return $this->belongsTo(BloodDonorRequest::class, 'blood_donor_request_id');
    }
}

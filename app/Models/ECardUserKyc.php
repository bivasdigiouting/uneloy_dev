<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ECardUserKyc extends Model
{
    use HasFactory;

    protected $table = 'ecard_kyc_documents';

    protected $fillable = [
        'ecard_registration_id',
        'aadhaar_front',
        'aadhaar_back',
        'pan_front',
        'pan_back',
        'cheque_book',
        'business_document',
        'business_photo',
        'signature',
    ];

    public function registration()
    {
        return $this->belongsTo(ECardRegistration::class, 'ecard_registration_id');
    }
}

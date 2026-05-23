<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvertisementRequest extends Model
{
    protected $fillable = [
        'campaign_name',
        'business_category_id',
        'lead_id',
        'location',
        'advertisement_type',
        'from_date',
        'to_date',
        'requester_type',
        'request_status',
        'requester_id',
        'requester_name',
        'requester_email',
    ];
}

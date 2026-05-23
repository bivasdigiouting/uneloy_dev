<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateLinkClick extends Model
{
    use HasFactory;

    protected $fillable = [
        'affiliate_link_id',
        'ip_address',
        'user_agent',
        'referer',
    ];

    public function affiliateLink()
    {
        return $this->belongsTo(AffiliateLink::class);
    }
}

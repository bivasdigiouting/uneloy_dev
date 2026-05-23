<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class AffiliateLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'affiliate_id',
        'link_name',
        'code',
        'destination_url',
        'start_date',
        'end_date',
        'status',
        'clicks_count',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'clicks_count' => 'int',
    ];

    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class);
    }

    public function clicks()
    {
        return $this->hasMany(AffiliateLinkClick::class);
    }

    public function isActiveOnDate(?Carbon $date = null): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        $date = ($date ?: now())->startOfDay();

        if ($this->start_date && $date->lt($this->start_date->startOfDay())) {
            return false;
        }

        if ($this->end_date && $date->gt($this->end_date->startOfDay())) {
            return false;
        }

        return true;
    }
}

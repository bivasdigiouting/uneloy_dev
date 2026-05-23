<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UtilityAffiliateLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'audience_type',
        'district_id',
        'city_id',
        'type',
        'from_date',
        'to_date',
        'link',
    ];

    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date',
    ];

    public function states()
    {
        return $this->belongsToMany(State::class, 'utility_affiliate_link_states', 'utility_affiliate_link_id', 'state_id')
            ->withTimestamps();
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}

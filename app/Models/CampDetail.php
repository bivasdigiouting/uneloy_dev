<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CampDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'camp_id',
        'state_id',
        'district_id',
        'city_id',
        'title',
        'capacity',
        'from_date',
        'to_date',
        'banner',
        'short_description',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'from_date' => 'date',
        'to_date' => 'date',
    ];

    public function camp()
    {
        return $this->belongsTo(Camp::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function getBannerUrlAttribute(): ?string
    {
        return $this->banner ? Storage::url($this->banner) : null;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_name',
        'state_id',
        'district_id',
        'city_id',
        'icon',
    ];

    protected $appends = ['icon_url'];

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

    public function getIconUrlAttribute(): ?string
    {
        if ($this->icon && Storage::disk('public')->exists($this->icon)) {
            return Storage::disk('public')->url($this->icon);
        }

        return null;
    }
}

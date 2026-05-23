<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Helpline extends Model
{
    use HasFactory;

    protected $fillable = [
        'helpline_name',
        'helpline_number',
        'state_id',
        'district_id',
        'city_id',
        'icon',
    ];

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
        return $this->icon ? Storage::url($this->icon) : null;
    }
}

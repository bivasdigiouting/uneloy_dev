<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EpsLevelUserDistribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'distribution_id',
        'level_type',
        'registration_id',
        'amount',
    ];

    protected $casts = [
        'amount' => 'float',
    ];

    public function distribution()
    {
        return $this->belongsTo(EpsLevelDistribution::class, 'distribution_id');
    }

    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }
}

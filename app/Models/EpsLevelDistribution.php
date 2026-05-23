<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EpsLevelDistribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'total_amount',
        'commission_source_type',
        'commission_source_id',
        'commission_breakdown',
        'created_by_user_id',
    ];

    protected $casts = [
        'total_amount' => 'float',
        'commission_breakdown' => 'array',
    ];

    public function userDistributions()
    {
        return $this->hasMany(EpsLevelUserDistribution::class, 'distribution_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }
}

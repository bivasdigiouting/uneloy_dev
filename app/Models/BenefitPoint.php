<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BenefitPoint extends Model
{
    protected $table = 'benefit_points';

    protected $fillable = [
        'type',
        'point',
    ];
}

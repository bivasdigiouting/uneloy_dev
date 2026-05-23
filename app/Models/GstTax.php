<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GstTax extends Model
{
    protected $fillable = [
        'tax_name',
        'rate_percent',
        'is_active',
    ];
}

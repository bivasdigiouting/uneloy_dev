<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InhouseProductCategory extends Model
{
    use HasFactory;

    protected $table = 'inhouse_product_categories';

    protected $fillable = [
        'code',
        'name',
        'slug',
        'icon',
        'display_order',
        'description',
        'status',
    ];

    protected $casts = [
        'display_order' => 'integer',
    ];
}

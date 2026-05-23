<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ECardModule extends Model
{
    protected $fillable = [
        'title',
        'key',
        'parent_id',
        'route_name',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ECardModule::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(ECardModule::class, 'parent_id')->orderBy('sort_order');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAadhaar extends Model
{
    protected $table = 'user_aadhaar';

    protected $fillable = [
        'user_id',
        'front_image',
        'back_image',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

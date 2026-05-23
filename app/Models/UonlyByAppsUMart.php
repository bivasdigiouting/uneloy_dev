<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UonlyByAppsUMart extends Model
{
    protected $table = 'uonly_by_apps_u_marts';

    protected $fillable = [
        'image',
        'text_header',
        'text_description',
        'footer_short_description',
    ];

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/'.$this->image) : null;
    }
}

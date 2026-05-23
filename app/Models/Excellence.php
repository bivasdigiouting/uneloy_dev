<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Excellence extends Model
{
    use HasFactory;

    protected $table = 'excellences';

    protected $fillable = [
        'image',
        'text_header',
        'text_description',
        'footer_short_description',
    ];

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}

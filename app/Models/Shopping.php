<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shopping extends Model
{
    protected $table = 'shoppings';

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

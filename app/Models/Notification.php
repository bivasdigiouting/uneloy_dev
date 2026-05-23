<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'send_to',
        'title',
        'description',
        'image_path',
        'is_sent',
        'sent_at',
    ];

    public static function validationRules(): array
    {
        return [
            'send_to' => 'required|in:ecard,ecard_seva,vendor',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];
    }
}

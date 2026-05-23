<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $fillable = [
        'name',
        'image',
        'email',
        'designation',
        'contact_no',
        'facebook_link',
        'twitter_link',
        'linkedin_link',
        'instagram_link',
        'status',
    ];

    /**
     * Get the full URL for the image.
     */
    public function getImageUrlAttribute()
    {
        if ($this->image && file_exists(public_path($this->image))) {
            return asset($this->image);
        }
        return null;
    }
}

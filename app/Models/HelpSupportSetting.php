<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HelpSupportSetting extends Model
{
    protected $table = 'help_support_settings';

    protected $fillable = [
        'page_title',
        'intro_text',
        'support_email',
        'support_phone',
        'support_whatsapp',
        'live_chat_url',
        'support_address',
        'working_hours',
        'additional_info',
    ];
}

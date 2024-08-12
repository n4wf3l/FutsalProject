<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'theme_color_primary',
        'theme_color_secondary',
        'logo',
        'club_name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
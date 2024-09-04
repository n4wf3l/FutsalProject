<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClubInfo extends Model
{
    use HasFactory;

    protected $table = 'club_info'; 

    protected $fillable = [
        'sportcomplex_location',
        'phone',
        'email',
        'federation_logo',
        'facebook',
        'instagram',
        'president',
        'latitude',
        'longitude',
        'city',
    ];
}

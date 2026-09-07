<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerFeminine extends Model
{
    use HasFactory;

    protected $table = 'players_feminines';

    protected $fillable = [
        'first_name',
        'last_name',
        'photo',
        'birthdate',
        'position',
        'number',
        'nationality',
        'height',
    ];
}

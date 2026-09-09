<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerEspoir extends Model
{
    use HasFactory;

    protected $table = 'players_espoirs';

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

    protected $hidden = ['birthdate', 'height'];
}

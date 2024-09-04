<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerU21 extends Model
{
    use HasFactory;

    protected $table = 'playersu21';

    
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

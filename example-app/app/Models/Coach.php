<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coach extends Model
{
    use HasFactory;


    protected $fillable = [
        'first_name',
        'last_name',
        'birth_date',
        'coaching_since',
        'birth_city',
        'nationality',
        'description',
        'photo',
    ];
}

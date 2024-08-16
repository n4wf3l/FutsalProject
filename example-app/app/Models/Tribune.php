<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tribune extends Model
{
    use HasFactory;

    protected $fillable = [
    'name', 'description', 'price', 'photo', 'currency', 'available_seats'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    use HasFactory;

    protected $fillable = [
        'season_label',
        'season_start_year',
        'division',
        'position',
        'position_label',
        'cup_result',
        'coach',
        'badge',
        'notes',
    ];

    protected $casts = [
        'season_start_year' => 'integer',
        'position' => 'integer',
    ];
}

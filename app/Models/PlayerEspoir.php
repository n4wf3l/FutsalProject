<?php

namespace App\Models;

use Carbon\Carbon;
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

    protected $hidden = ['birthdate'];

    protected $appends = ['age'];

    public function getAgeAttribute(): ?int
    {
        if (! $this->birthdate) {
            return null;
        }
        return Carbon::parse($this->birthdate)->age;
    }
}

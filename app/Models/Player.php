<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name', 'last_name', 'photo', 'birthdate',
        'position', 'number', 'nationality', 'height', 'contract_until'
    ];

    protected $hidden = ['birthdate', 'height'];

    protected $appends = ['age'];

    public function getAgeAttribute(): ?int
    {
        if (! $this->birthdate) {
            return null;
        }
        return Carbon::parse($this->birthdate)->age;
    }
}

<?php

namespace App\Models;

use Carbon\Carbon;
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

    protected $hidden = ['birth_date'];

    protected $appends = ['age'];

    public function getAgeAttribute(): ?int
    {
        if (! $this->birth_date) {
            return null;
        }
        return Carbon::parse($this->birth_date)->age;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PressRelease extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'image', 'slug'];

    // Génération du slug à partir du titre
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($pressRelease) {
            if (empty($pressRelease->slug)) {
                $pressRelease->slug = Str::slug($pressRelease->title);
            }
        });
    }
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d-m-Y');
    }
}

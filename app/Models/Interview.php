<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Interview extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'interviewee_name',
        'interviewee_role',
        'interviewee_affiliation',
        'hero_image',
        'interviewee_photo',
        'video_url',
        'excerpt',
        'quote_highlight',
        'content',
        'user_id',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    public function scopeDraft(Builder $query): Builder
    {
        return $query->whereNull('published_at');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}

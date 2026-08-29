<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerApplication extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_REVIEWED = 'reviewed';
    public const STATUS_CONTACTED = 'contacted';
    public const STATUS_ACCEPTED = 'accepted';
    public const STATUS_REJECTED = 'rejected';

    public const STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_REVIEWED,
        self::STATUS_CONTACTED,
        self::STATUS_ACCEPTED,
        self::STATUS_REJECTED,
    ];

    public const CATEGORY_JUNIOR = 'junior';
    public const CATEGORY_FEMININE = 'feminine';
    public const CATEGORY_SENIOR_MASCULINE = 'senior_masculine';

    public const CATEGORIES = [
        self::CATEGORY_JUNIOR => 'Équipe junior',
        self::CATEGORY_FEMININE => 'Équipe féminine',
        self::CATEGORY_SENIOR_MASCULINE => 'Équipe senior masculine',
    ];

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'birthdate',
        'nationality',
        'city',
        'category',
        'position_preference',
        'current_club',
        'experience_years',
        'message',
        'cv_path',
        'status',
        'reviewed_by_user_id',
        'reviewed_at',
        'admin_notes',
        'deletion_token',
    ];

    protected $casts = [
        'birthdate' => 'date',
        'reviewed_at' => 'datetime',
    ];

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by_user_id');
    }

    public function categoryLabel(): string
    {
        return self::CATEGORIES[$this->category] ?? $this->category;
    }
}

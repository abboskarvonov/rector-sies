<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Appeal extends Model
{
    const STATUS_PENDING    = 'pending';
    const STATUS_REVIEWING  = 'reviewing';
    const STATUS_RESPONDED  = 'responded';
    const STATUS_CLOSED     = 'closed';
    const STATUS_REJECTED   = 'rejected';

    const STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_REVIEWING,
        self::STATUS_RESPONDED,
        self::STATUS_CLOSED,
        self::STATUS_REJECTED,
    ];

    protected $fillable = [
        'tracking_code',
        'category_id',
        'full_name',
        'passport_number',
        'phone',
        'email',
        'subject',
        'body',
        'status',
        'files',
        'ip_address',
    ];

    protected $casts = [
        'files'      => 'array',
        'created_at' => 'datetime',
    ];

    public $timestamps = false;

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Appeal $appeal) {
            if (empty($appeal->tracking_code)) {
                $appeal->tracking_code = static::generateTrackingCode();
            }

            $appeal->created_at = now();
        });
    }

    private static function generateTrackingCode(): string
    {
        $year = now()->year;

        $lastNumber = static::whereYear('created_at', $year)->count();

        return sprintf('APP-%d-%05d', $year, $lastNumber + 1);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function statuses(): HasMany
    {
        return $this->hasMany(AppealStatus::class);
    }

    public function responses(): HasMany
    {
        return $this->hasMany(AppealResponse::class);
    }
}

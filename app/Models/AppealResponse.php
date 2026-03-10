<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppealResponse extends Model
{
    protected $fillable = [
        'appeal_id',
        'response_text',
        'responded_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public $timestamps = false;

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (AppealResponse $model) {
            $model->created_at = now();
        });
    }

    public function appeal(): BelongsTo
    {
        return $this->belongsTo(Appeal::class);
    }

    public function respondedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responded_by');
    }
}

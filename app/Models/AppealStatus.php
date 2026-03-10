<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppealStatus extends Model
{
    protected $fillable = [
        'appeal_id',
        'status',
        'comment',
        'changed_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public $timestamps = false;

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (AppealStatus $model) {
            $model->created_at = now();
        });
    }

    public function appeal(): BelongsTo
    {
        return $this->belongsTo(Appeal::class);
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduleProgress extends Model
{
    protected $table = 'schedule_progress';
    
    protected $fillable = [
        'schedule_item_id',
        'student_id',
        'week_start_date',
        'status',
        'completed_at',
        'student_notes',
    ];

    protected function casts(): array
    {
        return [
            'week_start_date' => 'date',
            'completed_at' => 'datetime',
        ];
    }

    public function scheduleItem(): BelongsTo
    {
        return $this->belongsTo(ScheduleItem::class, 'schedule_item_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // Helper methods
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    public function isNotStarted(): bool
    {
        return $this->status === 'not_started';
    }
}


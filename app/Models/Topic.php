<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Topic extends Model
{
    protected $fillable = [
        'course_id',
        'name',
        'order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function subTopics(): HasMany
    {
        return $this->hasMany(SubTopic::class)->orderBy('order');
    }

    public function questionLogs(): HasMany
    {
        return $this->hasMany(QuestionLog::class);
    }
}

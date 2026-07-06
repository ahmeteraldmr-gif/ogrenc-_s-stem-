<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    protected $fillable = [
        'field_id',
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

    public function field(): BelongsTo
    {
        return $this->belongsTo(Field::class);
    }

    public function topics(): HasMany
    {
        return $this->hasMany(Topic::class)->orderBy('order');
    }

    public function questionLogs(): HasMany
    {
        return $this->hasMany(QuestionLog::class);
    }

    public function studentAssignments(): HasMany
    {
        return $this->hasMany(StudentAssignment::class);
    }
}

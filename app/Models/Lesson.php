<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Lesson extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'module_id',
        'title',
        'content',
        'video_url',
        'order',
        'estimated_minutes',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'lesson_user')
                    ->withPivot('started_at', 'completed_at', 'progress_percentage')
                    ->withTimestamps();
    }

    public function getIsCompletedAttribute(): bool
    {
        return $this->users()->wherePivot('completed_at', '!=', null)->exists();
    }
}

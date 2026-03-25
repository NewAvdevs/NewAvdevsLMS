<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizAttempt extends Model
{
    protected $fillable = [
        'user_id',
        'quiz_id',
        'answers',
        'score',
        'passed',
        'attempt_number',
        'completed_at',
    ];

    protected $casts = [
        'answers' => 'array',
        'score' => 'float',
        'passed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Question extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'quiz_id',
        'question_text',
        'options',
        'correct_option_index',
        'question_type',
        'order',
    ];

    protected $casts = [
        'options' => 'array',
        'correct_option_index' => 'integer',
    ];

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }
}

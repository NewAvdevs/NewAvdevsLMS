<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Services\ScoreCalculator;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    protected ScoreCalculator $scoreCalculator;

    public function __construct(ScoreCalculator $scoreCalculator)
    {
        $this->scoreCalculator = $scoreCalculator;
    }

    public function show(Quiz $quiz)
    {
        return response()->json($quiz->load('questions', 'lesson'));
    }

    public function submit(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'answers' => 'required|array',
        ]);

        $user = auth()->user();

        // Check attempt limit
        $attemptCount = $user->quizAttempts()
            ->where('quiz_id', $quiz->id)
            ->count();

        if ($attemptCount >= $quiz->max_attempts) {
            return response()->json([
                'error' => 'Maximum attempts reached',
            ], 429);
        }

        // Calculate score
        $correctKeys = $quiz->questions->pluck('correct_option_index', 'id')->toArray();
        $score = $this->scoreCalculator->calculate($validated['answers'], $correctKeys);
        $passed = $score >= $quiz->passing_percentage;

        // Create quiz attempt
        $attempt = QuizAttempt::create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'answers' => $validated['answers'],
            'score' => $score,
            'passed' => $passed,
            'attempt_number' => $attemptCount + 1,
            'completed_at' => now(),
        ]);

        return response()->json([
            'message' => 'Quiz submitted successfully',
            'attempt' => $attempt,
            'score' => $score,
            'passed' => $passed,
        ]);
    }

    public function userAttempts(Quiz $quiz)
    {
        $attempts = auth()->user()->quizAttempts()
            ->where('quiz_id', $quiz->id)
            ->latest()
            ->get();

        return response()->json($attempts);
    }
}

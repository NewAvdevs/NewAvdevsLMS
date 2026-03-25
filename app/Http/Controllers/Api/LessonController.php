<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Services\ProgressService;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    protected ProgressService $progressService;

    public function __construct(ProgressService $progressService)
    {
        $this->progressService = $progressService;
    }

    public function show(Lesson $lesson)
    {
        return response()->json($lesson->load('quizzes.questions', 'module'));
    }

    public function complete(Request $request, Lesson $lesson)
    {
        $user = auth()->user();

        $lesson->users()->syncWithoutDetaching([
            $user->id => [
                'completed_at' => now(),
                'progress_percentage' => 100,
            ]
        ]);

        return response()->json([
            'message' => 'Lesson completed successfully',
            'lesson' => $lesson,
        ]);
    }

    public function userProgress(Lesson $lesson)
    {
        $user = auth()->user();
        $progress = $lesson->users()
            ->where('user_id', $user->id)
            ->first()
            ?->pivot;

        return response()->json([
            'lesson_id' => $lesson->id,
            'started_at' => $progress?->started_at,
            'completed_at' => $progress?->completed_at,
            'progress_percentage' => $progress?->progress_percentage ?? 0,
        ]);
    }
}

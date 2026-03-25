<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Module;
use App\Services\ProgressService;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    protected ProgressService $progressService;

    public function __construct(ProgressService $progressService)
    {
        $this->progressService = $progressService;
    }

    public function userProgress(Request $request)
    {
        $user = auth()->user();
        $data = $this->progressService->getUserProgressData($user);

        return response()->json([
            'user_id' => $user->id,
            'courses' => $data,
        ]);
    }

    public function courseProgress(Request $request, Course $course)
    {
        $user = auth()->user();
        $progress = $this->progressService->getCourseProgress($user, $course);

        return response()->json([
            'course_id' => $course->id,
            'course_title' => $course->title,
            'progress_percentage' => $progress,
        ]);
    }

    public function moduleProgress(Request $request, Module $module)
    {
        $user = auth()->user();
        $progress = $this->progressService->getModuleProgress($user, $module);

        return response()->json([
            'module_id' => $module->id,
            'module_title' => $module->title,
            'progress_percentage' => $progress,
        ]);
    }
}

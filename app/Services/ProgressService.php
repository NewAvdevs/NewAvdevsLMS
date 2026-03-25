<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Module;
use App\Models\User;

class ProgressService
{
    /**
     * Calculate course completion percentage for a user
     *
     * @param User $user
     * @param Course $course
     * @return float Percentage (0-100)
     */
    public function getCourseProgress(User $user, Course $course): float
    {
        $lessons = $course->modules()
            ->with('lessons')
            ->get()
            ->flatMap(fn($module) => $module->lessons);

        $totalLessons = $lessons->count();

        if ($totalLessons === 0) {
            return 0.0;
        }

        $completedLessons = $lessons->filter(fn($lesson) => 
            $user->lessons()->where('lesson_id', $lesson->id)
                ->wherePivot('completed_at', '!=', null)
                ->exists()
        )->count();

        return round(($completedLessons / $totalLessons) * 100, 2);
    }

    /**
     * Calculate module completion percentage for a user
     *
     * @param User $user
     * @param Module $module
     * @return float Percentage (0-100)
     */
    public function getModuleProgress(User $user, Module $module): float
    {
        $totalLessons = $module->lessons()->count();

        if ($totalLessons === 0) {
            return 0.0;
        }

        $completedLessons = $user->lessons()
            ->whereIn('lessons.id', $module->lessons->pluck('id'))
            ->wherePivot('completed_at', '!=', null)
            ->count();

        return round(($completedLessons / $totalLessons) * 100, 2);
    }

    /**
     * Get comprehensive user progress data
     *
     * @param User $user
     * @return array
     */
    public function getUserProgressData(User $user): array
    {
        $courses = Course::where('is_published', true)->get();
        $progressData = [];

        foreach ($courses as $course) {
            $progressData[$course->id] = [
                'course_id' => $course->id,
                'course_title' => $course->title,
                'progress_percentage' => $this->getCourseProgress($user, $course),
                'modules' => $this->getModulesProgress($user, $course),
            ];
        }

        return $progressData;
    }

    /**
     * Get all modules progress for a course
     *
     * @param User $user
     * @param Course $course
     * @return array
     */
    private function getModulesProgress(User $user, Course $course): array
    {
        return $course->modules->map(fn($module) => [
            'module_id' => $module->id,
            'module_title' => $module->title,
            'progress_percentage' => $this->getModuleProgress($user, $module),
        ])->toArray();
    }
}

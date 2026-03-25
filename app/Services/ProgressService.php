<?php

namespace App\Services;

class ProgressService {
    protected $lessons;
    protected $modules;
    protected $courses;

    public function __construct($lessons, $modules, $courses) {
        $this->lessons = $lessons;
        $this->modules = $modules;
        $this->courses = $courses;
    }

    public function calculateLessonCompletionPercentage() {
        $completed = 0;
        foreach ($this->lessons as $lesson) {
            if ($lesson['completed']) {
                $completed++;
            }
        }
        return ($completed / count($this->lessons)) * 100;
    }

    public function calculateModuleCompletionPercentage() {
        $completed = 0;
        foreach ($this->modules as $module) {
            if ($module['completed']) {
                $completed++;
            }
        }
        return ($completed / count($this->modules)) * 100;
    }

    public function calculateCourseCompletionPercentage() {
        $completed = 0;
        foreach ($this->courses as $course) {
            if ($course['completed']) {
                $completed++;
            }
        }
        return ($completed / count($this->courses)) * 100;
    }
}
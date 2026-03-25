<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::where('is_published', true)
            ->with('modules.lessons')
            ->paginate(15);

        return response()->json($courses);
    }

    public function show(Course $course)
    {
        return response()->json($course->load('modules.lessons.quizzes'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Course::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'thumbnail_url' => 'nullable|string|url',
            'is_published' => 'boolean',
        ]);

        $course = Course::create([
            ...$validated,
            'created_by' => auth()->id(),
        ]);

        return response()->json($course, 201);
    }

    public function update(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $validated = $request->validate([
            'title' => 'string|max:255',
            'description' => 'string',
            'thumbnail_url' => 'nullable|string|url',
            'is_published' => 'boolean',
        ]);

        $course->update($validated);

        return response()->json($course);
    }

    public function destroy(Course $course)
    {
        $this->authorize('delete', $course);
        $course->delete();

        return response()->json(['message' => 'Course deleted']);
    }
}

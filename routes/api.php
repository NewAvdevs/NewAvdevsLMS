<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\LessonController;
use App\Http\Controllers\Api\QuizController;
use App\Http\Controllers\Api\ProgressController;
use App\Http\Controllers\Api\CertificateController;

// Public authentication routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:api')->group(function () {
    // Auth
    Route::post('/auth/refresh', [AuthController::class, 'refresh']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);
    
    // Courses
    Route::apiResource('courses', CourseController::class);
    
    // Lessons
    Route::apiResource('lessons', LessonController::class)->only(['show']);
    Route::post('/lessons/{lesson}/complete', [LessonController::class, 'complete']);
    Route::get('/lessons/{lesson}/progress', [LessonController::class, 'userProgress']);
    
    // Quizzes
    Route::apiResource('quizzes', QuizController::class)->only(['show']);
    Route::post('/quizzes/{quiz}/submit', [QuizController::class, 'submit']);
    Route::get('/quizzes/{quiz}/attempts', [QuizController::class, 'userAttempts']);
    
    // Progress
    Route::get('/progress', [ProgressController::class, 'userProgress']);
    Route::get('/progress/courses/{course}', [ProgressController::class, 'courseProgress']);
    Route::get('/progress/modules/{module}', [ProgressController::class, 'moduleProgress']);
    
    // Certificates
    Route::apiResource('certificates', CertificateController::class)->only(['index', 'show']);
    Route::get('/certificates/{certificate}/download', [CertificateController::class, 'download']);
    Route::post('/courses/{course}/certificate', [CertificateController::class, 'generateForCourse']);
});

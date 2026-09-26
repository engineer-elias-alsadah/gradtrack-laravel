<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EdgeCaseController;
use App\Http\Controllers\MilestoneController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SubmissionController;
use Illuminate\Support\Facades\Route;

// Redirect root to dashboard or login
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

// Quick Switcher for Defense Demonstration
Route::get('/quick-login/{userId}', [AuthController::class, 'quickLogin'])->name('quick-login');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Projects
    Route::get('/projects/{project:slug}', [ProjectController::class, 'show'])->name('projects.show');

    // Milestones
    Route::get('/projects/{project:slug}/milestones/{milestone}', [MilestoneController::class, 'show'])->name('milestones.show');

    // Submissions
    Route::post('/projects/{project:slug}/milestones/{milestone}/submissions', [SubmissionController::class, 'store'])->name('submissions.store');

    // Supervisor Reviews
    Route::post('/projects/{project:slug}/milestones/{milestone}/submissions/{submission}/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    // Edge Cases Defense Showcase
    Route::get('/edge-cases', [EdgeCaseController::class, 'index'])->name('edge-cases.index');
});

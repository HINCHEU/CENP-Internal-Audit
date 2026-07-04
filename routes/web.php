<?php

use App\Http\Controllers\AuditEventController;
use App\Http\Controllers\AuditFindingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\MyAuditController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserEvaluationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check() && auth()->user()->role !== 'admin') {
        return redirect()->route('audits.index');
    }

    // return redirect()->route('dashboard');
    return redirect()->route('user-evaluations.index');
});

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware(['guest', 'throttle:5,1']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->name('register.post')->middleware(['guest', 'throttle:3,1']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Quick Evaluations (Public user side)
Route::get('/evaluations/{evaluation}', [UserEvaluationController::class, 'show'])->name('user-evaluations.show');
Route::post('/evaluations/{evaluation}/score', [UserEvaluationController::class, 'storeScore'])
    ->name('user-evaluations.score')
    ->middleware('throttle:evaluation-submissions');
Route::get('/evaluations/{evaluation}/thank-you', [UserEvaluationController::class, 'thankYou'])->name('user-evaluations.thank-you');

// Authenticated Routes
Route::middleware('auth')->group(function () {

    // My Audits (Available to ALL authenticated users)
    Route::get('/my-audits', [MyAuditController::class, 'index'])->name('audits.index');
    Route::get('/my-audits/{id}', [MyAuditController::class, 'viewSubmission'])->name('audits.show');
    Route::get('/my-audits/{id}/submit', [MyAuditController::class, 'show'])->name('audits.submit');
    Route::post('/my-audits/{id}/request-edit', [MyAuditController::class, 'requestEdit'])->name('audits.request-edit');
    Route::resource('audit-findings', AuditFindingController::class)->except(['index', 'show']);
    Route::get('/audit-findings/{id}', [AuditFindingController::class, 'show'])->name('audit-findings.show');

    // Quick Evaluations (User side index)
    Route::get('/evaluations', [UserEvaluationController::class, 'index'])->name('user-evaluations.index');

    // Admin-Only Routes
    Route::middleware('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('departments', DepartmentController::class);

        Route::resource('users', UserController::class);
        Route::post('/users/{user}/toggle-approval', [UserController::class, 'toggleApproval'])->name('users.toggle-approval');

        Route::resource('projects', ProjectController::class);

        Route::get('/audit-events/analytics/by-user', [AuditEventController::class, 'analyticByUser'])->name('audit-events.analytic-user');
        Route::get('/audit-events/analytics/by-project', [AuditEventController::class, 'analyticByProject'])->name('audit-events.analytic-project');
        Route::resource('audit-events', AuditEventController::class);

        Route::get('/admin-evaluations/analytics/by-user/export', [EvaluationController::class, 'exportAnalyticByUser'])->name('admin-evaluations.analytic-user.export');
        Route::get('/admin-evaluations/analytics/by-project/export', [EvaluationController::class, 'exportAnalyticByProject'])->name('admin-evaluations.analytic-project.export');

        Route::get('/admin-evaluations/analytics/by-user', [EvaluationController::class, 'analyticByUser'])->name('admin-evaluations.analytic-user');
        Route::get('/admin-evaluations/analytics/by-project', [EvaluationController::class, 'analyticByProject'])->name('admin-evaluations.analytic-project');
        Route::resource('admin-evaluations', EvaluationController::class);

        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

        Route::get('/audit-findings', [AuditFindingController::class, 'index'])->name('audit-findings.index');

        Route::post('/audit-findings/{id}/approve-edit', [AuditFindingController::class, 'approveEdit'])->name('audit-findings.approve-edit');
    });
});

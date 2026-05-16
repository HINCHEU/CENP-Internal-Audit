<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\AuditEventController;
use App\Http\Controllers\MyAuditController;
use App\Http\Controllers\AuditFindingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    
    // My Audits (Available to ALL authenticated users)
    Route::get('/my-audits', [MyAuditController::class, 'index'])->name('audits.index');
    Route::get('/my-audits/{id}/submit', [MyAuditController::class, 'show'])->name('audits.submit');
    Route::post('/my-audits/{id}/request-edit', [MyAuditController::class, 'requestEdit'])->name('audits.request-edit');
    Route::resource('audit-findings', AuditFindingController::class)->except(['index', 'show']);
    Route::get('/audit-findings/{id}', [AuditFindingController::class, 'show'])->name('audit-findings.show');

    // Admin-Only Routes
    Route::middleware('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        Route::resource('departments', DepartmentController::class);
        
        Route::resource('users', UserController::class);
        
        Route::resource('projects', ProjectController::class);
        
        Route::resource('audit-events', AuditEventController::class);
        
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        
        Route::post('/audit-findings/{id}/approve-edit', [AuditFindingController::class, 'approveEdit'])->name('audit-findings.approve-edit');
    });
});

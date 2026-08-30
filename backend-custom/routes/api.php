<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ResourceController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\SlaController;
use App\Http\Controllers\Api\WorkloadController;
use App\Http\Controllers\Api\AutomationController;
use App\Http\Controllers\Api\ReportController;

Route::prefix('auth')->group(function () {
    Route::post('signup', [AuthController::class, 'signup']);
    Route::post('signin', [AuthController::class, 'signin']);
});

Route::middleware('jwt.auth')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('me', [AuthController::class, 'me']);
    });

    Route::middleware('tenant')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index']);
        Route::get('sla', [SlaController::class, 'index']);
        Route::get('workload', [WorkloadController::class, 'index']);
        Route::get('reports/summary', [ReportController::class, 'summary']);

        Route::apiResource('tasks', TaskController::class);
        Route::apiResource('clients', ResourceController::class)->defaults('resource', 'clients');
        Route::apiResource('processes', ResourceController::class)->defaults('resource', 'processes');
        Route::apiResource('teams', ResourceController::class)->defaults('resource', 'teams');
        Route::apiResource('queues', ResourceController::class)->defaults('resource', 'queues');
        Route::apiResource('automations', AutomationController::class);
    });
});

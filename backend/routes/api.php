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

        /*
        |--------------------------------------------------------------------------
        | Dashboard / Operations
        |--------------------------------------------------------------------------
        */
        Route::get('dashboard', [
            DashboardController::class,
            'index'
        ]);

        Route::get('sla', [
            SlaController::class,
            'index'
        ]);

        Route::get('workload', [
            WorkloadController::class,
            'index'
        ]);

        Route::get('reports/summary', [
            ReportController::class,
            'summary'
        ]);

        /*
        |--------------------------------------------------------------------------
        | Tasks
        |--------------------------------------------------------------------------
        */
        Route::apiResource(
            'tasks',
            TaskController::class
        );

        /*
        |--------------------------------------------------------------------------
        | BPO Resources
        |--------------------------------------------------------------------------
        |
        | We are using explicit closures for the resource type instead of
        | Route::apiResource()->defaults(), because Laravel 13 does not
        | support defaults() on PendingResourceRegistration.
        |
        */

        Route::get('clients', function () {
            return app(ResourceController::class)
                ->index(request()->merge([
                    'resource' => 'clients'
                ]));
        });

        Route::post('clients', function () {
            return app(ResourceController::class)
                ->store(request()->merge([
                    'resource' => 'clients'
                ]));
        });

        Route::get('clients/{id}', function ($id) {
            return app(ResourceController::class)
                ->show(request(), $id);
        });

        Route::put('clients/{id}', function ($id) {
            return app(ResourceController::class)
                ->update(
                    request()->merge([
                        'resource' => 'clients'
                    ]),
                    $id
                );
        });

        Route::delete('clients/{id}', function ($id) {
            return app(ResourceController::class)
                ->destroy(request(), $id);
        });


        Route::get('processes', function () {
            return app(ResourceController::class)
                ->index(request()->merge([
                    'resource' => 'processes'
                ]));
        });

        Route::post('processes', function () {
            return app(ResourceController::class)
                ->store(request()->merge([
                    'resource' => 'processes'
                ]));
        });

        Route::get('processes/{id}', function ($id) {
            return app(ResourceController::class)
                ->show(request(), $id);
        });

        Route::put('processes/{id}', function ($id) {
            return app(ResourceController::class)
                ->update(
                    request()->merge([
                        'resource' => 'processes'
                    ]),
                    $id
                );
        });

        Route::delete('processes/{id}', function ($id) {
            return app(ResourceController::class)
                ->destroy(request(), $id);
        });


        Route::get('teams', function () {
            return app(ResourceController::class)
                ->index(request()->merge([
                    'resource' => 'teams'
                ]));
        });

        Route::post('teams', function () {
            return app(ResourceController::class)
                ->store(request()->merge([
                    'resource' => 'teams'
                ]));
        });

        Route::get('teams/{id}', function ($id) {
            return app(ResourceController::class)
                ->show(request(), $id);
        });

        Route::put('teams/{id}', function ($id) {
            return app(ResourceController::class)
                ->update(
                    request()->merge([
                        'resource' => 'teams'
                    ]),
                    $id
                );
        });

        Route::delete('teams/{id}', function ($id) {
            return app(ResourceController::class)
                ->destroy(request(), $id);
        });


        Route::get('queues', function () {
            return app(ResourceController::class)
                ->index(request()->merge([
                    'resource' => 'queues'
                ]));
        });

        Route::post('queues', function () {
            return app(ResourceController::class)
                ->store(request()->merge([
                    'resource' => 'queues'
                ]));
        });

        Route::get('queues/{id}', function ($id) {
            return app(ResourceController::class)
                ->show(request(), $id);
        });

        Route::put('queues/{id}', function ($id) {
            return app(ResourceController::class)
                ->update(
                    request()->merge([
                        'resource' => 'queues'
                    ]),
                    $id
                );
        });

        Route::delete('queues/{id}', function ($id) {
            return app(ResourceController::class)
                ->destroy(request(), $id);
        });

        /*
        |--------------------------------------------------------------------------
        | Automation
        |--------------------------------------------------------------------------
        */
        Route::apiResource(
            'automations',
            AutomationController::class
        );
    });
});
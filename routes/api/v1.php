<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\EmployeeController;
use App\Http\Controllers\Api\V1\TaskController;
use App\Http\Controllers\Api\V1\CostController;
use App\Http\Controllers\Api\V1\EarningController;
use App\Http\Controllers\Api\V1\SalaryController;
use App\Http\Controllers\Api\V1\AttendanceController;
use App\Http\Controllers\Api\V1\AdvanceController;
use App\Http\Controllers\Api\V1\DepartmentController;
use App\Http\Controllers\Api\V1\TaskTypeController;
use App\Http\Controllers\Api\V1\ReportController;

Route::prefix('v1')->group(function () {

    // Public authentication routes (no auth required)
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    // Authenticated routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::post('/auth/refresh', [AuthController::class, 'refresh']);

        // Routes available to all authenticated users (own data mainly)
        Route::get('/employees/me', [EmployeeController::class, 'stats']);

        // Admin/Manager-only routes
        Route::middleware('role:admin,manager')->group(function () {
            Route::apiResource('employees', EmployeeController::class);
            Route::put('employees/{employee}', [EmployeeController::class, 'update']);
            Route::patch('employees/{employee}', [EmployeeController::class, 'update']);

            Route::apiResource('tasks', TaskController::class);
            Route::put('tasks/{task}', [TaskController::class, 'update']);
            Route::patch('tasks/{task}', [TaskController::class, 'update']);
            Route::get('tasks/lookups', [TaskController::class, 'lookup']);

            Route::apiResource('costs', CostController::class);
            Route::put('costs/{cost}', [CostController::class, 'update']);
            Route::patch('costs/{cost}', [CostController::class, 'update']);
            Route::get('costs/cost-types', [CostController::class, 'costTypes']);

            Route::apiResource('earnings', EarningController::class);
            Route::put('earnings/{earning}', [EarningController::class, 'update']);
            Route::patch('earnings/{earning}', [EarningController::class, 'update']);

            Route::apiResource('salaries', SalaryController::class);
            Route::put('salaries/{salary}', [SalaryController::class, 'update']);
            Route::patch('salaries/{salary}', [SalaryController::class, 'update']);

            Route::apiResource('attendances', AttendanceController::class)->except(['create', 'edit']);
            Route::get('attendances/daily', [AttendanceController::class, 'dailyList']);
            Route::get('attendances/employee/{employee}', [AttendanceController::class, 'employeeList']);
            Route::put('attendances/{attendance}', [AttendanceController::class, 'update']);
            Route::patch('attendances/{attendance}', [AttendanceController::class, 'update']);

            Route::apiResource('advances', AdvanceController::class);
            Route::put('advances/{advance}', [AdvanceController::class, 'update']);
            Route::patch('advances/{advance}', [AdvanceController::class, 'update']);
            Route::get('advances/types', [AdvanceController::class, 'types']);

            Route::apiResource('departments', DepartmentController::class);
            Route::put('departments/{department}', [DepartmentController::class, 'update']);
            Route::patch('departments/{department}', [DepartmentController::class, 'update']);

            Route::apiResource('task-types', TaskTypeController::class)->parameters(['task-types' => 'task_type']);
            Route::put('task-types/{task_type}', [TaskTypeController::class, 'update']);
            Route::patch('task-types/{task_type}', [TaskTypeController::class, 'update']);

            Route::get('reports', [ReportController::class, 'index']);
            Route::get('reports/export', [ReportController::class, 'export']);
            Route::get('reports/dashboard', [ReportController::class, 'dashboard']);
        });
    });
});

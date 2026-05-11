<?php

use App\Http\Controllers\AdvanceController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CostController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EarningController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskTypeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect('/dashboard') : redirect('/login');
});

require __DIR__.'/auth.php';

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::post('/theme/set', function (Request $request) {
        $request->validate(['theme' => 'required|in:purple-yellow,purple-green,black-white']);
        session(['theme' => $request->theme]);

        return back();
    })->name('theme.set');

    Route::resource('employees', EmployeeController::class);
    Route::resource('advances', AdvanceController::class);
    Route::resource('salaries', SalaryController::class);
    Route::resource('costs', CostController::class);
    Route::get('costs/report', [CostController::class, 'report'])->name('costs.report');
    Route::resource('earnings', EarningController::class);
    Route::resource('attendances', AttendanceController::class);
    Route::get('attendances/daily/list', [AttendanceController::class, 'dailyList'])->name('attendances.daily');
    Route::get('attendances/employee/{employee}/list', [AttendanceController::class, 'employeeList'])->name('attendances.employee');
    Route::resource('tasks', TaskController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('task-types', TaskTypeController::class);
    Route::resource('users', UserController::class);
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');
});

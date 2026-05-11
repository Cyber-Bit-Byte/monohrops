<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

abstract class Controller
{
    protected function authorizeAdminOrManager(): void
    {
        $user = Auth::user();

        if (! in_array($user->role, ['admin', 'manager'])) {
            abort(403);
        }
    }

    protected function authorizeEmployeeOwnerOrManager($employee): void
    {
        $user = Auth::user();

        if ($user->role === 'admin' || $user->role === 'manager') {
            return;
        }

        if ($user->id === $employee->user_id) {
            return;
        }

        abort(403);
    }

    protected function authorizeRelatedEmployeeOwnerOrManager($model): void
    {
        $user = Auth::user();

        if ($user->role === 'admin' || $user->role === 'manager') {
            return;
        }

        $employee = $user->employee;

        if (! $employee || $employee->id !== $model->employee_id) {
            abort(403);
        }
    }
}

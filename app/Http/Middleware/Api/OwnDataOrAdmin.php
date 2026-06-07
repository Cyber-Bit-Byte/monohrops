<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class OwnDataOrAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user->isAdmin() || $user->isManager()) {
            return $next($request);
        }

        $route = $request->route();
        $employeeIdParam = null;

        foreach (['employee_id', 'employee', 'id'] as $param) {
            if ($route && $route->hasParameter($param)) {
                $employeeIdParam = $route->parameter($param);
                break;
            }
        }

        if ($employeeIdParam) {
            $currentEmployee = $user->employee;
            if ($currentEmployee && (int) $currentEmployee->id === (int) $employeeIdParam) {
                return $next($request);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Forbidden. You can only access your own data.',
        ], 403);
    }
}

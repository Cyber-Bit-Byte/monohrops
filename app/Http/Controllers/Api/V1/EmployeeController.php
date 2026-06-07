<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Employee::with('user', 'department');

        if (!$user->isAdmin() && !$user->isManager()) {
            $query->where('user_id', $user->id);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('position', 'like', '%' . $request->search . '%');
            });
        }

        $perPage = $request->get('per_page', 15);
        $employees = $query->orderBy('name')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => EmployeeResource::collection($employees),
            'meta' => [
                'current_page' => $employees->currentPage(),
                'last_page' => $employees->lastPage(),
                'per_page' => $employees->perPage(),
                'total' => $employees->total(),
            ],
        ], 200);
    }

    public function show(Request $request, Employee $employee)
    {
        $employee->loadMissing('user', 'department', 'salaries', 'costs', 'earnings', 'tasks');

        $user = $request->user();
        if (!$user->isAdmin() && !$user->isManager() && $user->employee->id !== $employee->id) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. You can only access your own profile.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => new EmployeeResource($employee),
        ], 200);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        if (!$user->isAdmin() && !$user->isManager()) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. Admin or Manager role required.',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:employees,email',
            'position' => 'nullable|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'hire_date' => 'nullable|date',
            'salary' => 'nullable|numeric',
            'employee_job_id' => 'nullable|string|max:255',
            'status' => 'nullable|in:active,inactive,on_leave',
            'user_id' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $employee = Employee::create($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Employee created successfully.',
            'data' => new EmployeeResource($employee->load('department')),
        ], 201);
    }

    public function update(Request $request, Employee $employee)
    {
        $user = $request->user();

        if (!$user->isAdmin() && !$user->isManager() && $user->employee->id !== $employee->id) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. You can only update your own profile.',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'email' => 'email|max:255|unique:employees,email,' . $employee->id,
            'position' => 'nullable|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'hire_date' => 'nullable|date',
            'salary' => 'nullable|numeric',
            'employee_job_id' => 'nullable|string|max:255',
            'status' => 'nullable|in:active,inactive,on_leave',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $employee->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Employee updated successfully.',
            'data' => new EmployeeResource($employee->load('department')),
        ], 200);
    }

    public function destroy(Request $request, Employee $employee)
    {
        $user = $request->user();
        if (!$user->isAdmin() && !$user->isManager()) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. Admin or Manager role required.',
            ], 403);
        }

        $employee->delete();

        return response()->json([
            'success' => true,
            'message' => 'Employee deleted successfully.',
        ], 200);
    }

    public function stats(Request $request)
    {
        $user = $request->user();
        $employeeId = $user->employee?->id;

        if (!$employeeId) {
            return response()->json([
                'success' => false,
                'message' => 'No employee record linked to this user.',
            ], 404);
        }

        $stats = [
            'total_tasks' => Task::where('employee_id', $employeeId)->count(),
            'completed_tasks' => Task::where('employee_id', $employeeId)->where('status', 'completed')->count(),
            'in_progress_tasks' => Task::where('employee_id', $employeeId)->where('status', 'in_progress')->count(),
        ];

        if ($user->isAdmin() || $user->isManager()) {
            $stats['total_employees'] = Employee::count();
            $stats['total_departments'] = \App\Models\Department::count();
        }

        return response()->json([
            'success' => true,
            'data' => $stats,
        ], 200);
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Models\Department;
use App\Models\Employee;
use App\Models\TaskType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Task::with(['employee.user', 'assignee.user', 'department', 'taskType']);

        if (!$user->isAdmin() && !$user->isManager()) {
            $userId = Auth::id();
            $query->where(function ($q) use ($userId) {
                $q->whereHas('employee', fn($sq) => $sq->where('user_id', $userId))
                  ->orWhereHas('assignee', fn($sq) => $sq->where('user_id', $userId));
            });
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        if ($request->filled('task_type_id')) {
            $query->where('task_type_id', $request->task_type_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('assignee_id')) {
            $query->where('assignee_id', $request->assignee_id);
        }
        if ($request->filled('from_date')) {
            $query->whereDate('due_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('due_date', '<=', $request->to_date);
        }

        $perPage = $request->get('per_page', 15);
        $tasks = $query->orderByDesc('due_date')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => TaskResource::collection($tasks),
            'meta' => [
                'current_page' => $tasks->currentPage(),
                'last_page' => $tasks->lastPage(),
                'per_page' => $tasks->perPage(),
                'total' => $tasks->total(),
            ],
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'due_date' => 'required|date',
            'employee_id' => 'required|exists:employees,id',
            'assignee_id' => 'required|exists:employees,id',
            'department_id' => 'nullable|exists:departments,id',
            'task_type_id' => 'nullable|exists:task_types,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $creatorEmployee = $user->employee;
        if (!$creatorEmployee) {
            return response()->json([
                'success' => false,
                'message' => 'Your account is not linked to an employee record.',
            ], 422);
        }

        $task = Task::create(array_merge($validator->validated(), ['employee_id' => $creatorEmployee->id]));

        return response()->json([
            'success' => true,
            'message' => 'Task created successfully.',
            'data' => new TaskResource($task->load('employee.user', 'assignee.user', 'department', 'taskType')),
        ], 201);
    }

    public function show(Request $request, Task $task)
    {
        $task->loadMissing('employee.user', 'assignee.user', 'department', 'taskType');

        $user = $request->user();
        if (!$user->isAdmin() && !$user->isManager()) {
            $employeeIds = [$task->employee_id, $task->assignee_id];
            if (!in_array($user->employee?->id, $employeeIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Forbidden.',
                ], 403);
            }
        }

        return response()->json([
            'success' => true,
            'data' => new TaskResource($task),
        ], 200);
    }

    public function update(Request $request, Task $task)
    {
        $user = $request->user();
        if (!$user->isAdmin() && !$user->isManager()) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. Admin or Manager role required.',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'in:pending,in_progress,completed,cancelled',
            'due_date' => 'date',
            'employee_id' => 'exists:employees,id',
            'assignee_id' => 'exists:employees,id',
            'department_id' => 'nullable|exists:departments,id',
            'task_type_id' => 'nullable|exists:task_types,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $task->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Task updated successfully.',
            'data' => new TaskResource($task->load('employee.user', 'assignee.user', 'department', 'taskType')),
        ], 200);
    }

    public function destroy(Request $request, Task $task)
    {
        $user = $request->user();
        if (!$user->isAdmin() && !$user->isManager()) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. Admin or Manager role required.',
            ], 403);
        }

        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully.',
        ], 200);
    }

    public function lookup(Request $request)
    {
        $user = $request->user();

        $departments = Department::orderBy('name')->get();
        $taskTypes = TaskType::orderBy('name')->get();
        $employees = Employee::query();

        if (!$user->isAdmin() && !$user->isManager()) {
            $employees->where('user_id', $user->id);
        }

        $employees = $employees->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => [
                'departments' => $departments,
                'task_types' => $taskTypes,
                'employees' => $employees,
            ],
        ], 200);
    }
}

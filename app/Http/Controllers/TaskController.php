<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Task;
use App\Models\TaskType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::with(['employee', 'assignee', 'department'])->orderBy('due_date');

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->input('department_id'));
        }

        if ($request->filled('task_type')) {
            $query->where('task_type', $request->input('task_type'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('from_date')) {
            $query->where('due_date', '>=', $request->input('from_date'));
        }

        if ($request->filled('to_date')) {
            $query->where('due_date', '<=', $request->input('to_date'));
        }

        if (! Auth::user()->isAdmin() && ! Auth::user()->isManager()) {
            $query->where(function ($q) {
                $q->whereHas('employee', function ($subQ) {
                    $subQ->where('user_id', Auth::id());
                })->orWhereHas('assignee', function ($subQ) {
                    $subQ->where('user_id', Auth::id());
                });
            });
        }

        $tasks = $query->paginate(10)->appends($request->only(['department_id', 'task_type', 'status', 'from_date', 'to_date']));
        $departments = Department::orderBy('name')->get();

        return view('tasks.index', compact('tasks', 'departments'));
    }

    public function create()
    {
        $this->authorizeAdminOrManager();

        $employees = Employee::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        $taskTypes = TaskType::orderBy('name')->get();

        return view('tasks.create', compact('employees', 'departments', 'taskTypes'));
    }

    public function store(Request $request)
    {
        $this->authorizeAdminOrManager();

        $allData = $request->all();

        $validated = $request->validate([
            'assignee_id' => 'required|exists:employees,id',
            'department_id' => 'nullable|exists:departments,id',
            'task_type_id' => 'nullable|exists:task_types,id',
            'title' => 'required|string|max:255',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
        ]);

        $creatorEmployee = Auth::user()->employee;

        if (! $creatorEmployee) {
            return redirect()->back()->with('error', 'Your account is not linked to an employee record.');
        }

        $validated['employee_id'] = $creatorEmployee->id;

        if (isset($allData['department_id'])) {
            $validated['department_id'] = $allData['department_id'];
        }
        if (isset($allData['task_type_id'])) {
            $validated['task_type_id'] = $allData['task_type_id'];
        }
        if (isset($allData['description'])) {
            $validated['description'] = $allData['description'];
        }

        $task = Task::create($validated);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function show(Task $task)
    {
        $this->authorizeRelatedEmployeeOwnerOrManager($task);

        $task->load('department', 'taskType');

        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $this->authorizeAdminOrManager();

        $employees = Employee::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        $taskTypes = TaskType::orderBy('name')->get();

        return view('tasks.edit', compact('task', 'employees', 'departments', 'taskTypes'));
    }

    public function update(Request $request, Task $task)
    {
        $this->authorizeAdminOrManager();

        $data = $request->validate([
            'assignee_id' => 'required|exists:employees,id',
            'department_id' => 'nullable|exists:departments,id',
            'task_type_id' => 'nullable|exists:task_types,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
        ]);

        $creatorEmployee = Auth::user()->employee;

        if (! $creatorEmployee) {
            return redirect()->back()->with('error', 'Your account is not linked to an employee record.');
        }

        $data['employee_id'] = $creatorEmployee->id;

        $task->update($data);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $this->authorizeAdminOrManager();

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\TaskType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isAdmin()) {
                abort(403, 'Unauthorized');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $taskTypes = TaskType::orderBy('name')->paginate(10);

        return view('task-types.index', compact('taskTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('task-types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:task_types',
        ]);

        TaskType::create($request->only('name'));

        return redirect()->route('task-types.index')->with('success', 'Task type created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TaskType $taskType)
    {
        return view('task-types.show', compact('taskType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TaskType $taskType)
    {
        return view('task-types.edit', compact('taskType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TaskType $taskType)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:task_types,name,' . $taskType->id,
        ]);

        $taskType->update($request->only('name'));

        return redirect()->route('task-types.index')->with('success', 'Task type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaskType $taskType)
    {
        $taskType->delete();

        return redirect()->route('task-types.index')->with('success', 'Task type deleted successfully.');
    }
}

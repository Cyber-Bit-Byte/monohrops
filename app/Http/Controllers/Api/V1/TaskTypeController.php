<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskTypeResource;
use App\Models\TaskType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaskTypeController extends Controller
{
    public function index()
    {
        $taskTypes = TaskType::orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => TaskTypeResource::collection($taskTypes),
        ], 200);
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isManager()) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. Admin or Manager role required.',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:task_types,name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $taskType = TaskType::create($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Task type created successfully.',
            'data' => new TaskTypeResource($taskType),
        ], 201);
    }

    public function show(TaskType $taskType)
    {
        return response()->json([
            'success' => true,
            'data' => new TaskTypeResource($taskType),
        ], 200);
    }

    public function update(Request $request, TaskType $taskType)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isManager()) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. Admin or Manager role required.',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:task_types,name,' . $taskType->id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $taskType->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Task type updated successfully.',
            'data' => new TaskTypeResource($taskType),
        ], 200);
    }

    public function destroy(Request $request, TaskType $taskType)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isManager()) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. Admin or Manager role required.',
            ], 403);
        }

        $taskType->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task type deleted successfully.',
        ], 200);
    }
}

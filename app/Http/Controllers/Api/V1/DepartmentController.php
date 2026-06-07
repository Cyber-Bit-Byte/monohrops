<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => DepartmentResource::collection($departments),
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
            'name' => 'required|string|max:255|unique:departments,name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $department = Department::create($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Department created successfully.',
            'data' => new DepartmentResource($department),
        ], 201);
    }

    public function show(Department $department)
    {
        $department->load('employees.user');

        return response()->json([
            'success' => true,
            'data' => new DepartmentResource($department),
        ], 200);
    }

    public function update(Request $request, Department $department)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isManager()) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. Admin or Manager role required.',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $department->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Department updated successfully.',
            'data' => new DepartmentResource($department),
        ], 200);
    }

    public function destroy(Request $request, Department $department)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isManager()) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. Admin or Manager role required.',
            ], 403);
        }

        $department->delete();

        return response()->json([
            'success' => true,
            'message' => 'Department deleted successfully.',
        ], 200);
    }
}

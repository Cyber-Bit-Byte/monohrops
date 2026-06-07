<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CostResource;
use App\Models\Cost;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CostController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Cost::with('employee.user', 'department');

        if (!$user->isAdmin() && !$user->isManager()) {
            $query->whereHas('employee', fn($sq) => $sq->where('user_id', $user->id));
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        if ($request->filled('cost_type')) {
            $query->where('cost_type', $request->cost_type);
        }
        if ($request->filled('from_date')) {
            $query->whereDate('date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('date', '<=', $request->to_date);
        }

        $perPage = $request->get('per_page', 15);
        $costs = $query->orderByDesc('date')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => CostResource::collection($costs),
            'meta' => [
                'current_page' => $costs->currentPage(),
                'last_page' => $costs->lastPage(),
                'per_page' => $costs->perPage(),
                'total' => $costs->total(),
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
            'description' => 'required|string|max:500',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'employee_id' => 'required|exists:employees,id',
            'department_id' => 'nullable|exists:departments,id',
            'cost_type' => 'required|in:' . implode(',', Cost::COST_TYPES),
            'notes' => 'nullable|string|max:1000',
            'document' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $cost = Cost::create($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Cost created successfully.',
            'data' => new CostResource($cost->load('employee.user', 'department')),
        ], 201);
    }

    public function show(Request $request, Cost $cost)
    {
        $cost->loadMissing('employee.user', 'department');

        if (!Auth::user()->isAdmin() && !Auth::user()->isManager() &&
            Auth::user()->employee?->id !== $cost->employee_id) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => new CostResource($cost),
        ], 200);
    }

    public function update(Request $request, Cost $cost)
    {
        $user = $request->user();
        if (!$user->isAdmin() && !$user->isManager()) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. Admin or Manager role required.',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'description' => 'string|max:500',
            'amount' => 'numeric|min:0',
            'date' => 'date',
            'employee_id' => 'exists:employees,id',
            'department_id' => 'nullable|exists:departments,id',
            'cost_type' => 'in:' . implode(',', Cost::COST_TYPES),
            'notes' => 'nullable|string|max:1000',
            'document' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $cost->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Cost updated successfully.',
            'data' => new CostResource($cost->load('employee.user', 'department')),
        ], 200);
    }

    public function destroy(Request $request, Cost $cost)
    {
        $user = $request->user();
        if (!$user->isAdmin() && !$user->isManager()) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. Admin or Manager role required.',
            ], 403);
        }

        $cost->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cost deleted successfully.',
        ], 200);
    }

    public function costTypes()
    {
        return response()->json([
            'success' => true,
            'data' => Cost::COST_TYPES,
        ], 200);
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\EarningResource;
use App\Models\Earning;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EarningController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Earning::with('employee.user', 'department');

        if (!$user->isAdmin() && !$user->isManager()) {
            $query->whereHas('employee', fn($sq) => $sq->where('user_id', $user->id));
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('from_date')) {
            $query->whereDate('date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('date', '<=', $request->to_date);
        }

        $perPage = $request->get('per_page', 15);
        $earnings = $query->orderByDesc('date')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => EarningResource::collection($earnings),
            'meta' => [
                'current_page' => $earnings->currentPage(),
                'last_page' => $earnings->lastPage(),
                'per_page' => $earnings->perPage(),
                'total' => $earnings->total(),
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
            'employee_id' => 'required|exists:employees,id',
            'department_id' => 'nullable|exists:departments,id',
            'type' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $earning = Earning::create($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Earning created successfully.',
            'data' => new EarningResource($earning->load('employee.user', 'department')),
        ], 201);
    }

    public function show(Request $request, Earning $earning)
    {
        $earning->loadMissing('employee.user', 'department');

        if (!Auth::user()->isAdmin() && !Auth::user()->isManager() &&
            Auth::user()->employee?->id !== $earning->employee_id) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => new EarningResource($earning),
        ], 200);
    }

    public function update(Request $request, Earning $earning)
    {
        $user = $request->user();
        if (!$user->isAdmin() && !$user->isManager()) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. Admin or Manager role required.',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'employee_id' => 'exists:employees,id',
            'department_id' => 'nullable|exists:departments,id',
            'type' => 'string|max:255',
            'amount' => 'numeric|min:0',
            'date' => 'date',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $earning->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Earning updated successfully.',
            'data' => new EarningResource($earning->load('employee.user', 'department')),
        ], 200);
    }

    public function destroy(Request $request, Earning $earning)
    {
        $user = $request->user();
        if (!$user->isAdmin() && !$user->isManager()) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. Admin or Manager role required.',
            ], 403);
        }

        $earning->delete();

        return response()->json([
            'success' => true,
            'message' => 'Earning deleted successfully.',
        ], 200);
    }
}

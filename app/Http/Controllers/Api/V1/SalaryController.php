<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\SalaryResource;
use App\Models\Salary;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SalaryController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Salary::with('employee.user');

        if (!$user->isAdmin() && !$user->isManager()) {
            $query->whereHas('employee', fn($sq) => $sq->where('user_id', $user->id));
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }
        if ($request->filled('from_date')) {
            $query->whereDate('date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('date', '<=', $request->to_date);
        }

        $perPage = $request->get('per_page', 15);
        $salaries = $query->orderByDesc('date')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => SalaryResource::collection($salaries),
            'meta' => [
                'current_page' => $salaries->currentPage(),
                'last_page' => $salaries->lastPage(),
                'per_page' => $salaries->perPage(),
                'total' => $salaries->total(),
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

        $salary = Salary::create($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Salary record created successfully.',
            'data' => new SalaryResource($salary->load('employee.user')),
        ], 201);
    }

    public function show(Request $request, Salary $salary)
    {
        $salary->loadMissing('employee.user');

        if (!Auth::user()->isAdmin() && !Auth::user()->isManager() &&
            Auth::user()->employee?->id !== $salary->employee_id) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => new SalaryResource($salary),
        ], 200);
    }

    public function update(Request $request, Salary $salary)
    {
        $user = $request->user();
        if (!$user->isAdmin() && !$user->isManager()) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. Admin or Manager role required.',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
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

        $salary->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Salary record updated successfully.',
            'data' => new SalaryResource($salary->load('employee.user')),
        ], 200);
    }

    public function destroy(Request $request, Salary $salary)
    {
        $user = $request->user();
        if (!$user->isAdmin() && !$user->isManager()) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. Admin or Manager role required.',
            ], 403);
        }

        $salary->delete();

        return response()->json([
            'success' => true,
            'message' => 'Salary record deleted successfully.',
        ], 200);
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdvanceResource;
use App\Models\Advance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdvanceController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Advance::with('employee.user');

        if (!$user->isAdmin() && !$user->isManager()) {
            $query->whereHas('employee', fn($sq) => $sq->where('user_id', $user->id));
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
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
        $advances = $query->orderByDesc('date')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => AdvanceResource::collection($advances),
            'meta' => [
                'current_page' => $advances->currentPage(),
                'last_page' => $advances->lastPage(),
                'per_page' => $advances->perPage(),
                'total' => $advances->total(),
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
            'type' => 'required|in:' . implode(',', array_keys(Advance::TYPES)),
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $advance = Advance::create($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Advance record created successfully.',
            'data' => new AdvanceResource($advance->load('employee.user')),
        ], 201);
    }

    public function show(Request $request, Advance $advance)
    {
        $advance->loadMissing('employee.user');

        if (!Auth::user()->isAdmin() && !Auth::user()->isManager() &&
            Auth::user()->employee?->id !== $advance->employee_id) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => new AdvanceResource($advance),
        ], 200);
    }

    public function update(Request $request, Advance $advance)
    {
        $user = $request->user();
        if (!$user->isAdmin() && !$user->isManager()) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. Admin or Manager role required.',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'type' => 'in:' . implode(',', array_keys(Advance::TYPES)),
            'amount' => 'numeric|min:0',
            'date' => 'date',
            'description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $advance->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Advance record updated successfully.',
            'data' => new AdvanceResource($advance->load('employee.user')),
        ], 200);
    }

    public function destroy(Request $request, Advance $advance)
    {
        $user = $request->user();
        if (!$user->isAdmin() && !$user->isManager()) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. Admin or Manager role required.',
            ], 403);
        }

        $advance->delete();

        return response()->json([
            'success' => true,
            'message' => 'Advance record deleted successfully.',
        ], 200);
    }

    public function types()
    {
        return response()->json([
            'success' => true,
            'data' => Advance::TYPES,
        ], 200);
    }
}

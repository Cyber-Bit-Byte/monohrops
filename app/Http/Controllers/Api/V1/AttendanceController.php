<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendanceResource;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Attendance::with('employee.user');

        if (!$user->isAdmin() && !$user->isManager()) {
            $query->whereHas('employee', fn($sq) => $sq->where('user_id', $user->id));
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }
        if ($request->filled('from_date')) {
            $query->whereDate('date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('date', '<=', $request->to_date);
        }

        $perPage = $request->get('per_page', 15);
        $attendances = $query->orderByDesc('date')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => AttendanceResource::collection($attendances),
            'meta' => [
                'current_page' => $attendances->currentPage(),
                'last_page' => $attendances->lastPage(),
                'per_page' => $attendances->perPage(),
                'total' => $attendances->total(),
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
            'date' => 'required|date',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i',
            'status' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $attendance = Attendance::create($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Attendance recorded successfully.',
            'data' => new AttendanceResource($attendance->load('employee.user')),
        ], 201);
    }

    public function show(Request $request, Attendance $attendance)
    {
        $attendance->loadMissing('employee.user');

        if (!Auth::user()->isAdmin() && !Auth::user()->isManager() &&
            Auth::user()->employee?->id !== $attendance->employee_id) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => new AttendanceResource($attendance),
        ], 200);
    }

    public function update(Request $request, Attendance $attendance)
    {
        $user = $request->user();
        if (!$user->isAdmin() && !$user->isManager()) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. Admin or Manager role required.',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'date' => 'date',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i',
            'status' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $attendance->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Attendance updated successfully.',
            'data' => new AttendanceResource($attendance->load('employee.user')),
        ], 200);
    }

    public function destroy(Request $request, Attendance $attendance)
    {
        $user = $request->user();
        if (!$user->isAdmin() && !$user->isManager()) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. Admin or Manager role required.',
            ], 403);
        }

        $attendance->delete();

        return response()->json([
            'success' => true,
            'message' => 'Attendance deleted successfully.',
        ], 200);
    }

    public function dailyList(Request $request)
    {
        $user = $request->user();
        $query = Attendance::with('employee.user')->orderByDesc('date');

        if (!$user->isAdmin() && !$user->isManager()) {
            $query->whereHas('employee', fn($sq) => $sq->where('user_id', $user->id));
        }

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        $perPage = $request->get('per_page', 50);
        $attendances = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => AttendanceResource::collection($attendances),
            'meta' => [
                'current_page' => $attendances->currentPage(),
                'last_page' => $attendances->lastPage(),
                'per_page' => $attendances->perPage(),
                'total' => $attendances->total(),
            ],
        ], 200);
    }

    public function employeeList(Request $request, Employee $employee)
    {
        $user = $request->user();
        if (!$user->isAdmin() && !$user->isManager() && $user->employee?->id !== $employee->id) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden.',
            ], 403);
        }

        $attendances = $employee->attendances()->orderByDesc('date')->paginate(50);

        return response()->json([
            'success' => true,
            'data' => AttendanceResource::collection($attendances),
            'meta' => [
                'current_page' => $attendances->currentPage(),
                'last_page' => $attendances->lastPage(),
                'per_page' => $attendances->perPage(),
                'total' => $attendances->total(),
            ],
        ], 200);
    }
}

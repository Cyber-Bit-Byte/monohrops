<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdminOrManager = $user->role === 'admin' || $user->role === 'manager';

        $query = Attendance::with('employee')->orderByDesc('date');

        if ($request->has('date') && $request->date) {
            $query->whereDate('date', $request->date);
        }

        if ($request->has('employee_id') && $request->employee_id) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->has('month') && $request->month) {
            $query->whereMonth('date', $request->month);
        }

        if ($request->has('year') && $request->year) {
            $query->whereYear('date', $request->year);
        }

        if (! $isAdminOrManager) {
            $query->whereHas('employee', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        $employees = Employee::with('department')->orderBy('name')->get();
        $attendances = $query->paginate(50);

        return view('attendances.index', compact('attendances', 'employees'));
    }

    public function create()
    {
        $this->authorizeAdminOrManager();

        $employees = Employee::with('department')->orderBy('name')->get();

        return view('attendances.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $this->authorizeAdminOrManager();

        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'check_in' => 'required|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i',
            'status' => 'required|in:present,absent,late,half-day,leave',
            'notes' => 'nullable|string|max:1000',
        ]);

        Attendance::create($data);

        return redirect()->route('attendances.index')->with('success', 'Attendance entry created successfully.');
    }

    public function show(Attendance $attendance)
    {
        $this->authorizeRelatedEmployeeOwnerOrManager($attendance);

        return view('attendances.show', compact('attendance'));
    }

    public function edit(Attendance $attendance)
    {
        $this->authorizeAdminOrManager();

        $employees = Employee::with('department')->orderBy('name')->get();

        return view('attendances.edit', compact('attendance', 'employees'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $this->authorizeAdminOrManager();

        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'check_in' => 'required|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i',
            'status' => 'required|in:present,absent,late,half-day,leave',
            'notes' => 'nullable|string|max:1000',
        ]);

        $attendance->update($data);

        return redirect()->route('attendances.index')->with('success', 'Attendance entry updated successfully.');
    }

    public function destroy(Attendance $attendance)
    {
        $this->authorizeAdminOrManager();

        $attendance->delete();

        return redirect()->route('attendances.index')->with('success', 'Attendance entry deleted successfully.');
    }

    public function dailyList(Request $request)
    {
        $date = $request->input('date', now()->toDateString());

        $attendances = Attendance::with('employee')
            ->whereDate('date', $date)
            ->orderBy('employee_id')
            ->get();

        $employees = Employee::with('department')->orderBy('name')->get();

        return view('attendances.daily', compact('attendances', 'employees', 'date'));
    }

    public function employeeList(Request $request, Employee $employee)
    {
        $employee->load('department');

        $query = Attendance::where('employee_id', $employee->id)
            ->orderByDesc('date');

        if ($request->filled('month')) {
            $query->whereMonth('date', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('date', $request->year);
        }

        $attendances = $query->paginate(31);
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        return view('attendances.employee', compact('attendances', 'employee', 'month', 'year'));
    }
}

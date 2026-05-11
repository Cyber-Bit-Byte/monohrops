<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Earning;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EarningController extends Controller
{
    public function index(Request $request)
    {
        $query = Earning::with(['employee', 'department'])->orderByDesc('date');

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->input('department_id'));
        }

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('from_date')) {
            $query->where('date', '>=', $request->input('from_date'));
        }

        if ($request->filled('to_date')) {
            $query->where('date', '<=', $request->input('to_date'));
        }

        if (! Auth::user()->isAdmin() && ! Auth::user()->isManager()) {
            $query->whereHas('employee', function ($query) {
                $query->where('user_id', Auth::id());
            });
        }

        $totalEarning = (clone $query)->sum('amount');
        $earnings = $query->paginate(10)->appends($request->only(['department_id', 'type', 'from_date', 'to_date']));
        $departments = Department::orderBy('name')->get();

        return view('earnings.index', compact('earnings', 'totalEarning', 'departments'));
    }

    public function create()
    {
        $this->authorizeAdminOrManager();

        $employees = Employee::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();

        return view('earnings.create', compact('employees', 'departments'));
    }

    public function store(Request $request)
    {
        $this->authorizeAdminOrManager();

        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'department_id' => 'nullable|required_with:employee_id|exists:departments,id',
            'type' => 'required|in:Account receivable,Cash,Advance',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        Earning::create($data);

        return redirect()->route('earnings.index')->with('success', 'Earning record created successfully.');
    }

    public function show(Earning $earning)
    {
        $this->authorizeRelatedEmployeeOwnerOrManager($earning);

        return view('earnings.show', compact('earning'));
    }

    public function edit(Earning $earning)
    {
        $this->authorizeAdminOrManager();

        $employees = Employee::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();

        return view('earnings.edit', compact('earning', 'employees', 'departments'));
    }

    public function update(Request $request, Earning $earning)
    {
        $this->authorizeAdminOrManager();

        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'department_id' => 'nullable|required_with:employee_id|exists:departments,id',
            'type' => 'required|in:Account receivable,Cash,Advance',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $earning->update($data);

        return redirect()->route('earnings.index')->with('success', 'Earning record updated successfully.');
    }

    public function destroy(Earning $earning)
    {
        $this->authorizeAdminOrManager();

        $earning->delete();

        return redirect()->route('earnings.index')->with('success', 'Earning record deleted successfully.');
    }
}

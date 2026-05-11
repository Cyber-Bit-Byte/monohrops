<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Salary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalaryController extends Controller
{
    public function index()
    {
        $query = Salary::with('employee')->orderByDesc('date');

        if (! Auth::user()->isAdmin() && ! Auth::user()->isManager()) {
            $query->whereHas('employee', function ($query) {
                $query->where('user_id', Auth::id());
            });
        }

        $salaries = $query->paginate(10);

        return view('salaries.index', compact('salaries'));
    }

    public function create()
    {
        $this->authorizeAdminOrManager();

        $employees = Employee::orderBy('name')->get();

        return view('salaries.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $this->authorizeAdminOrManager();

        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        Salary::create($data);

        return redirect()->route('salaries.index')->with('success', 'Salary record created successfully.');
    }

    public function show(Salary $salary)
    {
        $this->authorizeRelatedEmployeeOwnerOrManager($salary);

        return view('salaries.show', compact('salary'));
    }

    public function edit(Salary $salary)
    {
        $this->authorizeAdminOrManager();

        $employees = Employee::orderBy('name')->get();

        return view('salaries.edit', compact('salary', 'employees'));
    }

    public function update(Request $request, Salary $salary)
    {
        $this->authorizeAdminOrManager();

        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $salary->update($data);

        return redirect()->route('salaries.index')->with('success', 'Salary record updated successfully.');
    }

    public function destroy(Salary $salary)
    {
        $this->authorizeAdminOrManager();

        $salary->delete();

        return redirect()->route('salaries.index')->with('success', 'Salary record deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Cost;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CostController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdminOrManager = $user->role === 'admin' || $user->role === 'manager';

        $query = Cost::with(['employee', 'department']);

        if (! $isAdminOrManager) {
            $query->whereHas('employee', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('cost_type')) {
            $query->where('cost_type', $request->cost_type);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $costs = $query->orderByDesc('date')->get();
        $cost_list = Cost::with(['employee', 'department'])
            ->when(! $isAdminOrManager, function ($q) use ($user) {
                $q->whereHas('employee', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            })
            ->when($request->filled('employee_id'), fn ($q) => $q->where('employee_id', $request->employee_id))
            ->when($request->filled('cost_type'), fn ($q) => $q->where('cost_type', $request->cost_type))
            ->when($request->filled('department_id'), fn ($q) => $q->where('department_id', $request->department_id))
            ->when($request->filled('date_from'), fn ($q) => $q->whereDate('date', '>=', $request->date_from))
            ->when($request->filled('date_to'), fn ($q) => $q->whereDate('date', '<=', $request->date_to))
            ->orderByDesc('date')
            ->paginate(20);

        $byType = $costs->groupBy('cost_type')->map(fn ($group) => $group->sum('amount'));
        $byDepartment = $costs->groupBy(function ($cost) {
            return $cost->department->name ?? 'Unassigned';
        })->map(fn ($group) => $group->sum('amount'));
        $total = $costs->sum('amount');

        $employees = Employee::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();

        return view('costs.index', compact('costs', 'byType', 'byDepartment', 'total', 'employees', 'cost_list', 'departments'));
    }

    public function create()
    {
        $this->authorizeAdminOrManager();

        $employees = Employee::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();

        return view('costs.create', compact('employees', 'departments'));
    }

    public function store(Request $request)
    {
        $this->authorizeAdminOrManager();

        $data = $request->validate([
            'description' => 'required|string|max:1000',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'employee_id' => 'required|exists:employees,id',
            'department_id' => 'required|exists:departments,id',
            'cost_type' => ['required', Rule::in(Cost::COST_TYPES)],
            'notes' => 'nullable|string|max:1000',
            'document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        if ($request->hasFile('document')) {
            $data['document'] = $request->file('document')->store('cost-documents', 'public');
        }

        Cost::create($data);

        return redirect()->route('costs.index')->with('success', 'Cost entry created successfully.');
    }

    public function show(Cost $cost)
    {
        $this->authorizeRelatedEmployeeOwnerOrManager($cost);

        $cost->load('department');

        return view('costs.show', compact('cost'));
    }

    public function edit(Cost $cost)
    {
        $this->authorizeAdminOrManager();

        $employees = Employee::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();

        return view('costs.edit', compact('cost', 'employees', 'departments'));
    }

    public function update(Request $request, Cost $cost)
    {
        $this->authorizeAdminOrManager();

        $data = $request->validate([
            'description' => 'required|string|max:1000',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'employee_id' => 'required|exists:employees,id',
            'department_id' => 'required|exists:departments,id',
            'cost_type' => ['required', Rule::in(Cost::COST_TYPES)],
            'notes' => 'nullable|string|max:1000',
            'document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        if ($request->hasFile('document')) {
            if ($cost->document) {
                \Storage::disk('public')->delete($cost->document);
            }
            $data['document'] = $request->file('document')->store('cost-documents', 'public');
        }

        $cost->update($data);

        return redirect()->route('costs.index')->with('success', 'Cost entry updated successfully.');
    }

    public function destroy(Cost $cost)
    {
        $this->authorizeAdminOrManager();

        $cost->delete();

        return redirect()->route('costs.index')->with('success', 'Cost entry deleted successfully.');
    }

    public function report(Request $request)
    {
        $user = Auth::user();
        $isAdminOrManager = $user->role === 'admin' || $user->role === 'manager';

        $query = Cost::with(['employee', 'department']);

        if (! $isAdminOrManager) {
            $query->whereHas('employee', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('cost_type')) {
            $query->where('cost_type', $request->cost_type);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $costs = $query->get();

        $byType = $costs->groupBy('cost_type')->map(function ($group) {
            return $group->sum('amount');
        });

        $byDepartment = $costs->groupBy('department_id')->map(function ($group) {
            return $group->sum('amount');
        })->mapWithKeys(function ($amount, $departmentId) {
            $department = \App\Models\Department::find($departmentId);
            return [$department?->name ?? 'Unknown' => $amount];
        })->sortByKey();

        $total = $costs->sum('amount');

        $employees = Employee::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();

        return view('costs.report', compact('costs', 'byType', 'byDepartment', 'total', 'employees', 'departments'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Advance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;

class AdvanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $from = $request->input('from', now()->startOfMonth()->toDateString());
        $to = $request->input('to', now()->endOfMonth()->toDateString());

        $query = Advance::with('employee')->whereBetween('date', [$from, $to])->orderBy('date', 'desc');

        $totalAmount = Advance::whereBetween('date', [$from, $to])
            ->selectRaw("SUM(CASE WHEN type = 'taken_from_boss' THEN amount ELSE 0 END) as total")
            ->first()?->total;

        $totalAmount = $totalAmount !== null ? (float) $totalAmount : 0;

        $advances = $query->paginate(10);

        return view('advances.index', compact('advances', 'from', 'to', 'totalAmount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->authorizeAdminOrManager();
        $employees = Employee::orderBy('name')->get();

        return view('advances.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorizeAdminOrManager();

        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'type' => 'required|in:' . implode(',', array_keys(Advance::TYPES)),
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'description' => 'nullable|string|max:1000',
        ]);

        Advance::create($request->all());

        return redirect()->route('advances.index')->with('success', 'Advance created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Advance $advance): View
    {
        return view('advances.show', compact('advance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Advance $advance): View
    {
        $this->authorizeAdminOrManager();
        $employees = Employee::orderBy('name')->get();

        return view('advances.edit', compact('advance', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Advance $advance): RedirectResponse
    {
        $this->authorizeAdminOrManager();

        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'type' => 'required|in:' . implode(',', array_keys(Advance::TYPES)),
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'description' => 'nullable|string|max:1000',
        ]);

        $advance->update($request->all());

        return redirect()->route('advances.index')->with('success', 'Advance updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Advance $advance): RedirectResponse
    {
        $this->authorizeAdminOrManager();

        $advance->delete();

        return redirect()->route('advances.index')->with('success', 'Advance deleted successfully.');
    }
}

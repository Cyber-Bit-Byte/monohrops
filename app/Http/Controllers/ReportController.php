<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Cost;
use App\Models\Earning;
use App\Models\Salary;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->input('from', now()->startOfMonth()->toDateString());
        $to = $request->input('to', now()->endOfMonth()->toDateString());

        $salaryTotal = Salary::whereBetween('date', [$from, $to])->sum('amount');
        $costTotal = Cost::whereBetween('date', [$from, $to])->sum('amount');
        $earningTotal = Earning::whereBetween('date', [$from, $to])->sum('amount');
        $attendanceCount = Attendance::whereBetween('date', [$from, $to])->count();

        $costByDepartment = Cost::whereBetween('date', [$from, $to])
            ->selectRaw('department_id, SUM(amount) as total')
            ->groupBy('department_id')
            ->pluck('total', 'department_id')
            ->toArray();

        $reports = [
            'salary_total' => $salaryTotal,
            'cost_total' => $costTotal,
            'cost_by_department' => $costByDepartment,
            'earning_total' => $earningTotal,
            'attendance_count' => $attendanceCount,
        ];

        return view('reports.index', compact('from', 'to', 'reports'));
    }

    public function export(Request $request)
    {
        $from = $request->input('from', now()->startOfMonth()->toDateString());
        $to = $request->input('to', now()->endOfMonth()->toDateString());

        $rows = Salary::whereBetween('date', [$from, $to])
            ->with('employee')
            ->get()
            ->map(function ($salary) {
                return [
                    'employee' => $salary->employee->name,
                    'sequence' => $salary->employee->sequenc,
                    'amount' => number_format($salary->amount, 2),
                    'date' => $salary->date->format('Y-m-d'),
                    'notes' => $salary->notes,
                ];
            });

        $csv = implode(",", ['Employee', 'Sequence', 'Amount', 'Date', 'Notes']) . "\n";
        foreach ($rows as $row) {
            $csv .= implode(',', array_map(fn ($value) => '"' . str_replace('"', '""', $value) . '"', $row)) . "\n";
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="report-salaries-' . $from . '-to-' . $to . '.csv"',
        ]);
    }
}

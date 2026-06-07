<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Salary;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user->isAdmin() && !$user->isManager()) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. Admin or Manager role required.',
            ], 403);
        }

        $fromDate = $request->get('from_date', now()->startOfMonth()->toDateString());
        $toDate = $request->get('to_date', now()->endOfMonth()->toDateString());
        $departmentId = $request->get('department_id');

        $query = Salary::with('employee.department')
            ->whereBetween('date', [$fromDate, $toDate]);

        if ($departmentId) {
            $query->whereHas('employee', fn($sq) => $sq->where('department_id', $departmentId));
        }

        $salaries = $query->get();

        $report = [
            'period' => [
                'from' => $fromDate,
                'to' => $toDate,
            ],
            'total_salary' => $salaries->sum('amount'),
            'by_department' => $salaries->groupBy('employee.department.name')
                ->map(fn($items, $dept) => [
                    'department' => $dept,
                    'total' => $items->sum('amount'),
                    'count' => $items->count(),
                ])->values()->all(),
        ];

        return response()->json([
            'success' => true,
            'data' => $report,
        ], 200);
    }

    public function export(Request $request)
    {
        $user = $request->user();

        if (!$user->isAdmin() && !$user->isManager()) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. Admin or Manager role required.',
            ], 403);
        }

        $fromDate = $request->get('from_date', now()->startOfMonth()->toDateString());
        $toDate = $request->get('to_date', now()->endOfMonth()->toDateString());

        $salaries = Salary::with('employee:id,name,department_id', 'employee.department:id,name')
            ->whereBetween('date', [$fromDate, $toDate])
            ->get();

        $rows = $salaries->map(fn($s) => [
            'employee' => $s->employee->name ?? 'N/A',
            'department' => $s->employee->department->name ?? 'N/A',
            'amount' => $s->amount,
            'date' => $s->date->toDateString(),
            'notes' => $s->notes ?? '',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Export ready',
            'data' => $rows,
        ], 200);
    }

    public function dashboard(Request $request)
    {
        $user = $request->user();

        if (!$user->isAdmin() && !$user->isManager()) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. Admin or Manager role required.',
            ], 403);
        }

        $stats = [
            'total_employees' => Employee::count(),
            'total_tasks' => \App\Models\Task::count(),
            'total_costs' => \App\Models\Cost::sum('amount'),
            'total_earnings' => \App\Models\Earning::sum('amount'),
            'total_salaries' => Salary::sum('amount'),
            'tasks_by_status' => \App\Models\Task::selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status')
                ->all(),
            'recent_attendances' => \App\Models\Attendance::with('employee.user')
                ->orderByDesc('date')
                ->limit(10)
                ->get(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
        ], 200);
    }
}

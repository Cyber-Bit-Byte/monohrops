<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Salary;
use Illuminate\Database\Seeder;

class SalarySeeder extends Seeder
{
    public function run(): void
    {
        $employees = \App\Models\Employee::all();
        $months = [1, 2, 3, 4, 5]; // Jan through May 2026

        foreach ($employees as $employee) {
            foreach ($months as $month) {
                $date = \Carbon\Carbon::create(2026, $month, 28); // Last day of each month
                
                // Check if salary already exists for this employee and month
                $exists = \App\Models\Salary::where('employee_id', $employee->id)
                    ->whereMonth('date', $month)
                    ->whereYear('date', 2026)
                    ->exists();
                
                if (!$exists) {
                    \App\Models\Salary::create([
                        'employee_id' => $employee->id,
                        'amount' => $employee->salary / 12, // Monthly salary
                        'date' => $date,
                        'notes' => 'Monthly salary for ' . $date->format('F Y'),
                    ]);
                }
            }
        }
    }
}
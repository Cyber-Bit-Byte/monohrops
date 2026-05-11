<?php

namespace Database\Seeders;

use App\Models\Earning;
use App\Models\Employee;
use Illuminate\Database\Seeder;

class EarningSeeder extends Seeder
{
    public function run(): void
    {
        $employees = \App\Models\Employee::with('department')->get();
        $earningTypes = ['bonus', 'incentive', 'overtime', 'commission', 'award'];
        $startDate = now()->parse('2026-01-01');
        $endDate = now();

        foreach ($employees as $employee) {
            // Create 2-8 random earnings per employee
            $numEarnings = rand(2, 8);
            
            for ($i = 0; $i < $numEarnings; $i++) {
                $date = $startDate->copy()->addDays(rand(0, $startDate->diffInDays($endDate)));
                
                \App\Models\Earning::create([
                    'employee_id' => $employee->id,
                    'department_id' => $employee->department_id,
                    'type' => fake()->randomElement($earningTypes),
                    'amount' => rand(1000, 10000),
                    'date' => $date->format('Y-m-d'),
                    'notes' => fake()->sentence(),
                ]);
            }
        }
    }
}
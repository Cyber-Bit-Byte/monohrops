<?php

namespace Database\Seeders;

use App\Models\Cost;
use App\Models\Employee;
use Illuminate\Database\Seeder;

class CostSeeder extends Seeder
{
    public function run(): void
    {
        $employees = \App\Models\Employee::with('department')->get();
        $costTypes = \App\Models\Cost::COST_TYPES;
        $startDate = now()->parse('2026-01-01');
        $endDate = now();

        foreach ($employees as $employee) {
            // Create 5-15 random costs per employee
            $numCosts = rand(5, 15);
            
            for ($i = 0; $i < $numCosts; $i++) {
                $date = $startDate->copy()->addDays(rand(0, $startDate->diffInDays($endDate)));
                
                \App\Models\Cost::create([
                    'description' => fake()->sentence(4),
                    'amount' => rand(100, 5000),
                    'date' => $date->format('Y-m-d'),
                    'cost_type' => fake()->randomElement($costTypes),
                    'employee_id' => $employee->id,
                    'department_id' => $employee->department_id,
                    'notes' => fake()->paragraph(),
                ]);
            }
        }
    }
}
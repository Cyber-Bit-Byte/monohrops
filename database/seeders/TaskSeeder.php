<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $employees = \App\Models\Employee::all();
        $statuses = ['pending', 'in_progress', 'completed'];
        $taskTypes = [
            'Write script' => 'Prepare and submit the monthly project report.',
            'Record video' => 'Record product demonstration video for client presentation.',
            'Edit video' => 'Edit marketing video content for social media campaign.',
            'Publish video' => 'Upload and publish video content to YouTube channel.',
            'Graphic post' => 'Create promotional graphics for upcoming event.',
            'Publish news' => 'Write and publish company news article.',
            'Purchase ingredients' => 'Order supplies and ingredients for production.',
            'Purchase instrument' => 'Research and order new equipment.',
            'Bajar' => 'Go to bajar for office supplies procurement.',
            'Contact' => 'Follow up with clients and partners.',
        ];
        $startDate = now()->parse('2026-01-01');
        $endDate = now();

        foreach ($employees as $employee) {
            // Create 3-10 random tasks per employee
            $numTasks = rand(3, 10);
            
            for ($i = 0; $i < $numTasks; $i++) {
                $taskType = fake()->randomElement(array_keys($taskTypes));
                $dueDate = $startDate->copy()->addDays(rand(0, $startDate->diffInDays($endDate)));
                
                \App\Models\Task::create([
                    'employee_id' => $employee->id,
                    'title' => $taskType,
                    'description' => $taskTypes[$taskType],
                    'due_date' => $dueDate->format('Y-m-d'),
                    'status' => fake()->randomElement($statuses),
                ]);
            }
        }
    }
}
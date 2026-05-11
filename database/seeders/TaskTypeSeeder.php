<?php

namespace Database\Seeders;

use App\Models\TaskType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $taskTypes = [
            'write script',
            'record video',
            'edit video',
            'publish video',
            'video upload',
            'graphic post',
            'publish news',
            'purchase ingredients',
            'purchase instrument',
            'bajar',
            'contact',
            'others',
        ];

        foreach ($taskTypes as $taskType) {
            TaskType::create(['name' => $taskType]);
        }
    }
}

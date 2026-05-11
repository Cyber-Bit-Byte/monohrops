<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            'orik inc',
            'srot24',
            'dayoff express',
            'koto kotha',
            'deep haat',
            'd purple studio',
        ];

        foreach ($departments as $department) {
            Department::create(['name' => $department]);
        }
    }
}

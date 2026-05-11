<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@hrops.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        // Create manager user
        $manager = User::factory()->create([
            'name' => 'Manager User',
            'email' => 'manager@hrops.com',
            'role' => 'manager',
            'password' => bcrypt('password'),
        ]);

        // Create employee users
        $employee1 = User::factory()->create([
            'name' => 'Employee One',
            'email' => 'employee1@hrops.com',
            'role' => 'employee',
            'password' => bcrypt('password')
        ]);

        $employee2 = User::factory()->create([
            'name' => 'Employee Two',
            'email' => 'employee2@hrops.com',
            'role' => 'employee',
            'password' => bcrypt('password'),
        ]);

        // Seed departments first
        $this->call([
            DepartmentSeeder::class,
            TaskTypeSeeder::class,
        ]);

        // Get department IDs
        $deptOrik = \App\Models\Department::where('name', 'orik inc')->first();
        $deptSrot = \App\Models\Department::where('name', 'srot24')->first();
        $deptDayoff = \App\Models\Department::where('name', 'dayoff express')->first();
        $deptKoto = \App\Models\Department::where('name', 'koto kotha')->first();
        $deeptHaat = \App\Models\Department::where('name', 'deep haat')->first();
        $deptPurple = \App\Models\Department::where('name', 'd purple studio')->first();

        // Create employees with department_id
        $admin->employee()->create([
            'name' => 'Admin User',
            'email' => 'admin@hrops.com',
            'position' => 'Administrator',
            'department_id' => $deptOrik?->id,
            'hire_date' => now()->subYears(2),
            'salary' => 80000,
        ]);

        $manager->employee()->create([
            'name' => 'Manager User',
            'email' => 'manager@hrops.com',
            'position' => 'Manager',
            'department_id' => $deptSrot?->id,
            'hire_date' => now()->subYear(),
            'salary' => 60000,
        ]);

        $employee1->employee()->create([
            'name' => 'Employee One',
            'email' => 'employee1@hrops.com',
            'position' => 'Developer',
            'department_id' => $deptDayoff?->id,
            'hire_date' => now()->subMonths(6),
            'salary' => 50000,
        ]);

        $employee2->employee()->create([
            'name' => 'Employee Two',
            'email' => 'employee2@hrops.com',
            'position' => 'Designer',
            'department_id' => $deptKoto?->id,
            'hire_date' => now()->subMonths(3),
            'salary' => 45000,
        ]);

        // Seed other data
        $this->call([
            // DepartmentSeeder::class, // Already seeded
            // TaskTypeSeeder::class, // Already seeded above
            SalarySeeder::class,
            CostSeeder::class,
            EarningSeeder::class,
            AttendanceSeeder::class,
            TaskSeeder::class,
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $employees = \App\Models\Employee::all();
        $startDate = now()->parse('2026-01-01');
        $endDate = now();
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            // Skip weekends
            if ($currentDate->isWeekday()) {
                foreach ($employees as $employee) {
                    $checkIn = $currentDate->copy()->hour(8)->minute(rand(0, 59));
                    $checkOut = $currentDate->copy()->hour(17)->minute(rand(0, 59));
                    
                    // Some random absences/late/half-day
                    $rand = rand(1, 100);
                    if ($rand <= 5) { // 5% absent
                        $status = 'absent';
                        $checkIn = null;
                        $checkOut = null;
                        $notes = 'Absent';
                    } elseif ($rand <= 15) { // 10% late
                        $status = 'late';
                        $checkIn = $currentDate->copy()->hour(9)->minute(rand(0, 59));
                        $notes = 'Late arrival';
                    } elseif ($rand <= 20) { // 5% half-day
                        $status = 'half-day';
                        $checkOut = $currentDate->copy()->hour(13)->minute(rand(0, 59));
                        $notes = 'Half day leave';
                    } else { // 80% present
                        $status = 'present';
                        $notes = '';
                    }

                    \App\Models\Attendance::create([
                        'employee_id' => $employee->id,
                        'date' => $currentDate->format('Y-m-d'),
                        'check_in' => $checkIn ? $checkIn->format('H:i:s') : null,
                        'check_out' => $checkOut ? $checkOut->format('H:i:s') : null,
                        'status' => $status,
                        'notes' => $notes,
                    ]);
                }
            }
            $currentDate->addDay();
        }
    }
}
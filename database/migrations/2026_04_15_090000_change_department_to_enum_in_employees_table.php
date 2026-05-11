<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('employees') && Schema::hasColumn('employees', 'department')) {
            $allowed = [
                'orik inc',
                'srot24',
                'dayoff express',
                'koto kotha',
                'deep haat',
                'd purple studio',
            ];

            DB::table('employees')
                ->whereNotIn('department', $allowed)
                ->update(['department' => 'orik inc']);

            $quoted = implode("','", $allowed);
            DB::statement("ALTER TABLE `employees` MODIFY `department` ENUM('{$quoted}') NOT NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('employees') && Schema::hasColumn('employees', 'department')) {
            DB::statement('ALTER TABLE `employees` MODIFY `department` VARCHAR(255) NOT NULL');
        }
    }
};

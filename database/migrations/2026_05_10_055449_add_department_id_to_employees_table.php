<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->unsignedBigInteger('department_id')->nullable()->after('department');
        });

        // Map existing enum departments to department_id
        $departmentMap = [
            'orik inc' => 1,
            'srot24' => 2,
            'dayoff express' => 3,
            'koto kotha' => 4,
            'deep haat' => 5,
            'd purple studio' => 6,
        ];

        foreach ($departmentMap as $deptName => $deptId) {
            DB::table('employees')
                ->where('department', $deptName)
                ->update(['department_id' => $deptId]);
        }

        // Set department_id to 1 for any unmatched records
        DB::table('employees')
            ->whereNull('department_id')
            ->where('department', '!=', '')
            ->update(['department_id' => 1]);

        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('department');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('department_id');
            $table->string('department')->default('orik inc');
        });
    }
};

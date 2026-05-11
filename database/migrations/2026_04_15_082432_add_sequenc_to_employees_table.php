<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('employees', 'sequenc')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->string('sequenc')->nullable()->after('employee_job_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('employees', 'sequenc')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->dropColumn('sequenc');
            });
        }
    }
};

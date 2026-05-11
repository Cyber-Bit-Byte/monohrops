<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('earnings', function (Blueprint $table) {
            $table->enum('department', [
                'orik inc',
                'srot24',
                'dayoff express',
                'koto kotha',
                'deep haat',
                'd purple studio',
            ])->nullable()->after('employee_id');
        });
    }

    public function down(): void
    {
        Schema::table('earnings', function (Blueprint $table) {
            $table->dropColumn('department');
        });
    }
};

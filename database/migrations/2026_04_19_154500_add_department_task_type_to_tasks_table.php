<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->enum('department', [
                'orik inc',
                'srot24',
                'dayoff express',
                'koto kotha',
                'deep haat',
                'd purple studio',
            ])->nullable()->after('employee_id');
            $table->enum('task_type', [
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
            ])->nullable()->after('department');
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['department', 'task_type']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('study_schedules', function (Blueprint $table) {
            // 'timed' = saatli program, 'daily' = saatsiz günlük program
            $table->string('schedule_type')->default('timed')->after('is_template');
        });
        
        // time_slot'u nullable yap
        Schema::table('schedule_items', function (Blueprint $table) {
            $table->string('time_slot')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('study_schedules', function (Blueprint $table) {
            $table->dropColumn('schedule_type');
        });
        
        // time_slot'u tekrar not null yapmıyoruz
    }
};

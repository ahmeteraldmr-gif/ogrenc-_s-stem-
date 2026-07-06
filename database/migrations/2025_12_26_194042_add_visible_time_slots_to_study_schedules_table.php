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
        Schema::table('study_schedules', function (Blueprint $table) {
            // Hangi saat dilimlerinin görünür olduğunu tutar (JSON array)
            $table->json('visible_time_slots')->nullable()->after('schedule_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('study_schedules', function (Blueprint $table) {
            $table->dropColumn('visible_time_slots');
        });
    }
};

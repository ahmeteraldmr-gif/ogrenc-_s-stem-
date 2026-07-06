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
            $table->date('start_date')->nullable()->after('schedule_type');
            $table->date('end_date')->nullable()->after('start_date');
            $table->integer('duration_days')->nullable()->after('end_date');
            $table->integer('week_number')->default(1)->after('duration_days');
            $table->boolean('is_master_template')->default(false)->after('is_template');
            $table->foreignId('copied_from_schedule_id')->nullable()->after('is_master_template')
                ->constrained('study_schedules')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('study_schedules', function (Blueprint $table) {
            $table->dropForeign(['copied_from_schedule_id']);
            $table->dropColumn([
                'start_date',
                'end_date',
                'duration_days',
                'week_number',
                'is_master_template',
                'copied_from_schedule_id',
            ]);
        });
    }
};

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
        Schema::table('schedule_progress', function (Blueprint $table) {
            // Add new status column
            $table->enum('status', ['not_started', 'in_progress', 'completed'])
                ->default('not_started')
                ->after('week_start_date');
        });

        // Migrate existing data: is_completed=true => status='completed', else 'not_started'
        DB::statement("UPDATE schedule_progress SET status = 'completed' WHERE is_completed = 1");
        
        Schema::table('schedule_progress', function (Blueprint $table) {
            // Drop old column
            $table->dropColumn('is_completed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedule_progress', function (Blueprint $table) {
            $table->boolean('is_completed')->default(false)->after('week_start_date');
        });

        // Migrate back: status='completed' => is_completed=true
        DB::statement("UPDATE schedule_progress SET is_completed = 1 WHERE status = 'completed'");

        Schema::table('schedule_progress', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};

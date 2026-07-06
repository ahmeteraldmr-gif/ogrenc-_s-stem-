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
        Schema::create('schedule_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->date('week_start_date'); // Haftanın başlangıç tarihi (Pazartesi)
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->text('student_notes')->nullable();
            $table->timestamps();
            
            $table->unique(['schedule_item_id', 'student_id', 'week_start_date'], 'schedule_progress_unique');
            $table->index(['student_id', 'week_start_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_progress');
    }
};

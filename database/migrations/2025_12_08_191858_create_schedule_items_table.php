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
        Schema::create('schedule_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->constrained('study_schedules')->onDelete('cascade');
            $table->tinyInteger('day_of_week'); // 1=Pazartesi, 7=Pazar
            $table->string('time_slot'); // örn: "09:00-10:00"
            $table->foreignId('course_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('topic_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('sub_topic_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('question_count')->default(0);
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
            
            $table->index(['schedule_id', 'day_of_week']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_items');
    }
};

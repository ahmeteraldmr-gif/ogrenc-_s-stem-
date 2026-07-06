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
        Schema::create('exam_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->string('exam_name');
            $table->foreignId('course_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('correct_answers');
            $table->integer('wrong_answers');
            $table->integer('blank_answers');
            $table->decimal('net_score', 8, 2); // net = doğru - (yanlış / 4)
            $table->date('exam_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_results');
    }
};

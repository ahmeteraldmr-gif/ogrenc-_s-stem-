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
        Schema::table('question_logs', function (Blueprint $table) {
            $table->foreignId('topic_id')->nullable()->after('course_id')
                ->constrained('topics')->onDelete('set null');
            $table->foreignId('sub_topic_id')->nullable()->after('topic_id')
                ->constrained('sub_topics')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('question_logs', function (Blueprint $table) {
            $table->dropForeign(['topic_id']);
            $table->dropForeign(['sub_topic_id']);
            $table->dropColumn(['topic_id', 'sub_topic_id']);
        });
    }
};

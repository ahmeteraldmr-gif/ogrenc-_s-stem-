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
        Schema::table('exam_results', function (Blueprint $table) {
            $table->foreignId('field_id')->nullable()->after('course_id')
                ->constrained('fields')->onDelete('set null');
            $table->string('exam_type')->nullable()->after('exam_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_results', function (Blueprint $table) {
            $table->dropForeign(['field_id']);
            $table->dropColumn(['field_id', 'exam_type']);
        });
    }
};

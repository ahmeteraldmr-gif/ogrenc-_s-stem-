<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('resources', function (Blueprint $table) {
            $table->foreignId('field_id')->nullable()->after('name')->constrained()->onDelete('set null');
            $table->foreignId('course_id')->nullable()->after('field_id')->constrained()->onDelete('set null');
            $table->dropColumn('publisher');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resources', function (Blueprint $table) {
            $table->string('publisher')->nullable()->after('name');
            $table->dropForeign(['field_id']);
            $table->dropForeign(['course_id']);
            $table->dropColumn(['field_id', 'course_id']);
        });
    }
};

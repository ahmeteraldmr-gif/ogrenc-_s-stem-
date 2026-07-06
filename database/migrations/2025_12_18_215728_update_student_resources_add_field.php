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
        Schema::table('student_resources', function (Blueprint $table) {
            $table->foreignId('field_id')->nullable()->after('resource_id')
                ->constrained('fields')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_resources', function (Blueprint $table) {
            $table->dropForeign(['field_id']);
            $table->dropColumn('field_id');
        });
    }
};

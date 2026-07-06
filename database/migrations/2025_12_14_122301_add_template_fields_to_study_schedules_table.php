<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('study_schedules', function (Blueprint $table) {
            $table->boolean('is_template')->default(false)->after('is_active');
            $table->foreignId('student_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('study_schedules', function (Blueprint $table) {
            $table->dropColumn('is_template');
            // student_id'yi tekrar not null yapmıyoruz çünkü veri kaybı olabilir
        });
    }
};

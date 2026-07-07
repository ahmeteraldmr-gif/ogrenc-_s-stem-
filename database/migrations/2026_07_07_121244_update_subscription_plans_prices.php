<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\SubscriptionPlan;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update Başlangıç
        SubscriptionPlan::where('name', 'Başlangıç')->update([
            'price' => 300.00,
            'student_limit' => 10,
        ]);

        // Update Standart
        SubscriptionPlan::where('name', 'Standart')->update([
            'price' => 800.00,
            'student_limit' => 25,
        ]);

        // Update Premium
        SubscriptionPlan::where('name', 'Premium')->update([
            'price' => 999.00,
            'student_limit' => 50,
        ]);

        // Update Sınırsız
        SubscriptionPlan::where('name', 'Sınırsız')->update([
            'price' => 1250.00,
            'student_limit' => 100,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        SubscriptionPlan::where('name', 'Başlangıç')->update([
            'price' => 199.00,
            'student_limit' => 10,
        ]);

        SubscriptionPlan::where('name', 'Standart')->update([
            'price' => 399.00,
            'student_limit' => 25,
        ]);

        SubscriptionPlan::where('name', 'Premium')->update([
            'price' => 699.00,
            'student_limit' => 50,
        ]);

        SubscriptionPlan::where('name', 'Sınırsız')->update([
            'price' => 999.00,
            'student_limit' => null,
        ]);
    }
};

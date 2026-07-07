<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Başlangıç',
                'student_limit' => 10,
                'price' => 300.00,
                'trial_days' => 14,
                'is_active' => true,
            ],
            [
                'name' => 'Standart',
                'student_limit' => 25,
                'price' => 800.00,
                'trial_days' => 14,
                'is_active' => true,
            ],
            [
                'name' => 'Premium',
                'student_limit' => 50,
                'price' => 999.00,
                'trial_days' => 14,
                'is_active' => true,
            ],
            [
                'name' => 'Sınırsız',
                'student_limit' => 100,
                'price' => 1250.00,
                'trial_days' => 14,
                'is_active' => true,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::create($plan);
        }
    }
}

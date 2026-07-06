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
                'price' => 199.00,
                'trial_days' => 14,
                'is_active' => true,
            ],
            [
                'name' => 'Standart',
                'student_limit' => 25,
                'price' => 399.00,
                'trial_days' => 14,
                'is_active' => true,
            ],
            [
                'name' => 'Premium',
                'student_limit' => 50,
                'price' => 699.00,
                'trial_days' => 14,
                'is_active' => true,
            ],
            [
                'name' => 'Sınırsız',
                'student_limit' => null,
                'price' => 999.00,
                'trial_days' => 14,
                'is_active' => true,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::create($plan);
        }
    }
}

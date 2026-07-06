<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();

        $admin = User::firstOrCreate(
            ['email' => 'admin@ogrenci.com'],
            [
                'role_id' => $adminRole->id,
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );

        // Seed an active unlimited subscription for the admin
        if (!$admin->subscription) {
            $unlimitedPlan = \App\Models\SubscriptionPlan::where('name', 'Sınırsız')->first();
            if ($unlimitedPlan) {
                \App\Models\Subscription::create([
                    'user_id' => $admin->id,
                    'subscription_plan_id' => $unlimitedPlan->id,
                    'start_date' => \Carbon\Carbon::now(),
                    'end_date' => \Carbon\Carbon::now()->addYears(5),
                    'next_payment_date' => \Carbon\Carbon::now()->addYears(5),
                    'is_active' => true,
                    'is_trial' => false,
                ]);
            }
        }
    }
}

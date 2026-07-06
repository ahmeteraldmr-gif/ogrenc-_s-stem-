<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoCoachSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coachRole = Role::where('name', 'coach')->first();
        $startPlan = SubscriptionPlan::where('name', 'Başlangıç')->first();

        $coaches = [
            [
                'name' => 'Ahmet Yılmaz',
                'email' => 'coach1@ogrenci.com',
            ],
            [
                'name' => 'Ayşe Demir',
                'email' => 'coach2@ogrenci.com',
            ],
        ];

        foreach ($coaches as $coachData) {
            $coach = User::create([
                'role_id' => $coachRole->id,
                'name' => $coachData['name'],
                'email' => $coachData['email'],
                'password' => Hash::make('password'),
                'is_active' => true,
            ]);

            // Koça abonelik ata
            Subscription::create([
                'user_id' => $coach->id,
                'subscription_plan_id' => $startPlan->id,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonth(),
                'next_payment_date' => Carbon::now()->addMonth(),
                'is_active' => true,
                'is_trial' => true,
            ]);
        }
    }
}

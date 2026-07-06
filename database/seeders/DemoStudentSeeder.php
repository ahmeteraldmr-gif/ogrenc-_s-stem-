<?php

namespace Database\Seeders;

use App\Models\CoachStudent;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studentRole = Role::where('name', 'student')->first();
        $coaches = User::whereHas('role', fn($q) => $q->where('name', 'coach'))->get();

        $students = [
            'Ali Kaya',
            'Zeynep Şahin',
            'Mehmet Çelik',
            'Fatma Arslan',
            'Can Yıldız',
            'Elif Öztürk',
        ];

        $studentIndex = 0;
        foreach ($coaches as $coach) {
            // Her koça 3 öğrenci ata
            for ($i = 0; $i < 3; $i++) {
                if ($studentIndex >= count($students)) break;
                
                $student = User::create([
                    'role_id' => $studentRole->id,
                    'name' => $students[$studentIndex],
                    'email' => 'student' . ($studentIndex + 1) . '@ogrenci.com',
                    'password' => Hash::make('password'),
                    'is_active' => true,
                ]);

                // Koç-öğrenci ilişkisi oluştur
                CoachStudent::create([
                    'coach_id' => $coach->id,
                    'student_id' => $student->id,
                    'is_active' => true,
                ]);

                $studentIndex++;
            }
        }
    }
}

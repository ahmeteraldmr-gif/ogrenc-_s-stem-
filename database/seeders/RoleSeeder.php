<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'admin', 'display_name' => 'Admin'],
            ['name' => 'coach', 'display_name' => 'Koç'],
            ['name' => 'student', 'display_name' => 'Öğrenci'],
            ['name' => 'superadmin', 'display_name' => 'Super Admin'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name']], ['display_name' => $role['display_name']]);
        }
    }
}

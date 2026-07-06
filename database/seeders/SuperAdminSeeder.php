<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get Super Admin Role
        $superAdminRole = Role::where('name', 'superadmin')->first();

        // Create Super Admin User
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'bozoglanahmet02@gmail.com',
            'password' => Hash::make('Abozoglan01.'),
            'role_id' => $superAdminRole->id,
        ]);
    }
}

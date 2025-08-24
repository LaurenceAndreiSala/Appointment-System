<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Models\User;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            ['description' => 'System administrator']
        );

        $doctorRole = Role::firstOrCreate(
            ['name' => 'doctor'],
            ['description' => 'Medical doctor']
        );

        $patientRole = Role::firstOrCreate(
            ['name' => 'patient'],
            ['description' => 'Patient user']
        );

        // Create default admin account
        User::firstOrCreate(
            ['email' => 'admin@example.com'], // unique check
            [
                'firstname' => 'System',
                'lastname' => 'Admin',
                'username' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin123'), // 🔐 change this later
                'role_id' => $adminRole->id,
            ]
        );
    }
}

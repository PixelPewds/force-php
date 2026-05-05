<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roles = [
            'Super Admin',
            'Admin',
            'Student'
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        $user = User::create([
            'name' => "Force",
            'email' => "admin@admin.com",
            'password' => "12345",
            'mobile' => "0801234567",
            'gender' => "male",
            'address' => "Address is the new way",
            'status' => "Y"
        ]);

        $user->assignRole('Super Admin');
    }
}

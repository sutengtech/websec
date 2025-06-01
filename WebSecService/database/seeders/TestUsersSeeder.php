<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {        // Create admin user
        $admin = User::firstOrCreate([
            'email' => 'admin@example.com'
        ], [
            'name' => 'Admin User',
            'password' => Hash::make('password123'),
        ]);

        // Assign Admin role
        $adminRole = Role::where('name', 'Admin')->first();
        if ($adminRole && !$admin->hasRole('Admin')) {
            $admin->assignRole($adminRole);
            $this->command->info('Admin user created and assigned Admin role');
        }

        // Create exteacher user
        $exteacher = User::firstOrCreate([
            'email' => 'exteacher@example.com'
        ], [
            'name' => 'External Teacher',
            'password' => Hash::make('password123'),
        ]);

        // Assign exteacher role
        $exteacherRole = Role::where('name', 'exteacher')->first();
        if ($exteacherRole && !$exteacher->hasRole('exteacher')) {
            $exteacher->assignRole($exteacherRole);
            $this->command->info('Exteacher user created and assigned exteacher role');
        }

        // Create regular user
        $regularUser = User::firstOrCreate([
            'email' => 'user@example.com'
        ], [
            'name' => 'Regular User',
            'password' => Hash::make('password123'),
        ]);

        $this->command->info('Regular user created without special roles');
        $this->command->info('Test users created successfully!');
        $this->command->info('Login credentials:');
        $this->command->info('Admin: admin@example.com / password123');
        $this->command->info('Exteacher: exteacher@example.com / password123');
        $this->command->info('Regular: user@example.com / password123');
    }
}

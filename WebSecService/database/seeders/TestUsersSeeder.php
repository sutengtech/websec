<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::firstOrCreate([
            'email' => 'admin@example.com'
        ], [
            'name' => 'Admin User',
            'password' => Hash::make('Qwe!2345'),
        ]);

        // Assign Admin role
        $adminRole = Role::where('name', 'Admin')->first();
        if ($adminRole && !$admin->hasRole('Admin')) {
            $admin->assignRole($adminRole);
            $this->command->info('Admin user created and assigned Admin role');
        }

        // Create teacher user
        $teacher = User::firstOrCreate([
            'email' => 'teacher@example.com'
        ], [
            'name' => 'Teacher User',
            'password' => Hash::make('Qwe!2345'),
        ]);        // Assign exteacher role
        $exteacherRole = Role::where('name', 'exteacher')->first();
        if ($exteacherRole && !$teacher->hasRole('exteacher')) {
            $teacher->assignRole($exteacherRole);
            $this->command->info('Teacher user created and assigned exteacher role');
        }

        // Create manager user
        $manager = User::firstOrCreate([
            'email' => 'manager@example.com'
        ], [
            'name' => 'Manager User',
            'password' => Hash::make('Qwe!2345'),
        ]);

        // Assign exmanager role
        $exmanagerRole = Role::where('name', 'exmanager')->first();
        if ($exmanagerRole && !$manager->hasRole('exmanager')) {
            $manager->assignRole($exmanagerRole);
            $this->command->info('Manager user created and assigned exmanager role');
        }

        // Create student user
        $student = User::firstOrCreate([
            'email' => 'student@example.com'
        ], [
            'name' => 'Student User',
            'password' => Hash::make('Qwe!2345'),
        ]);

        // Assign exstudent role
        $exstudentRole = Role::where('name', 'exstudent')->first();
        if ($exstudentRole && !$student->hasRole('exstudent')) {
            $student->assignRole($exstudentRole);
            $this->command->info('Student user created and assigned exstudent role');
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
        $this->command->info('Admin: admin@example.com / Qwe!2345');
        $this->command->info('Teacher: teacher@example.com / Qwe!2345');
        $this->command->info('Manager: manager@example.com / Qwe!2345');
        $this->command->info('Student: student@example.com / Qwe!2345');
        $this->command->info('Regular: user@example.com / password123');
    }
}

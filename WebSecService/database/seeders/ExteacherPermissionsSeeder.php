<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ExteacherPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create the new permissions if they don't exist
        $newPermissions = [
            'show_exgrades' => 'View Student Grades',
            'edit_exgrades' => 'Edit Student Grades',
        ];

        foreach ($newPermissions as $name => $displayName) {
            Permission::firstOrCreate(
                ['name' => $name, 'guard_name' => 'web'],
                ['display_name' => $displayName]
            );
            $this->command->info("Created permission: {$name}");
        }

        // Create the exteacher role if it doesn't exist
        $exteacherRole = Role::firstOrCreate(['name' => 'exteacher', 'guard_name' => 'web']);
          // Assign the grade-related permissions to exteacher role
        $exteacherPermissions = ['show_exgrades', 'edit_exgrades'];
        $exteacherRole->syncPermissions($exteacherPermissions);

        $this->command->info('Exteacher role and permissions created successfully.');
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AppealPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create appeal-related permissions
        $appealPermissions = [
            'submit_grade_appeal' => 'Submit Grade Appeals',
            'respond_to_grade_appeal' => 'Respond to Grade Appeals',
            'view_grade_appeals' => 'View Grade Appeals',
        ];        foreach ($appealPermissions as $name => $displayName) {
            Permission::firstOrCreate(
                ['name' => $name, 'guard_name' => 'web']
            );
        }

        // Assign appeal permissions to roles
        
        // Students can submit appeals
        $exstudentRole = Role::where('name', 'exstudent')->first();
        if ($exstudentRole) {
            $exstudentRole->givePermissionTo('submit_grade_appeal');
        }

        // Teachers and managers can respond to appeals and view them
        $exteacherRole = Role::where('name', 'exteacher')->first();
        if ($exteacherRole) {
            $exteacherRole->givePermissionTo(['respond_to_grade_appeal', 'view_grade_appeals']);
        }

        $exmanagerRole = Role::where('name', 'exmanager')->first();
        if ($exmanagerRole) {
            $exmanagerRole->givePermissionTo(['respond_to_grade_appeal', 'view_grade_appeals']);
        }

        // Admins get all appeal permissions
        $adminRole = Role::where('name', 'Admin')->first();
        if ($adminRole) {
            $adminRole->givePermissionTo(array_keys($appealPermissions));
        }

        $this->command->info('Appeal permissions created and assigned successfully.');
    }
}

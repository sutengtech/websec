<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class BasicRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create basic permissions if they don't exist
        $permissions = [
            'add_products' => 'Add Products',
            'edit_products' => 'Edit Products',
            'delete_products' => 'Delete Products',
            'show_users' => 'Show Users',
            'edit_users' => 'Edit Users',
            'delete_users' => 'Delete Users',
            'admin_users' => 'Administer Users',
            'view_products' => 'View Products',
            'purchase_products' => 'Purchase Products',
            'view_own_profile' => 'View Own Profile',
            'manage_stock' => 'Manage Stock',
            'manage_customer_credit' => 'Manage Customer Credit',
            'hold_products' => 'Hold Products',
            'show_exgrades' => 'View Student Grades',
            'edit_exgrades' => 'Edit Student Grades',
        ];

        foreach ($permissions as $name => $displayName) {
            Permission::firstOrCreate(
                ['name' => $name, 'guard_name' => 'web'],
                ['display_name' => $displayName]
            );
        }

        // Create Admin Role if it doesn't exist
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        
        // Assign all permissions to Admin
        $adminRole->syncPermissions(array_keys($permissions));

        // Create Employee Role if it doesn't exist
        $employeeRole = Role::firstOrCreate(['name' => 'Employee', 'guard_name' => 'web']);
        
        // Assign specific permissions to Employee
        $employeePermissions = [
            'add_products', 'edit_products', 'show_users', 'edit_users',
            'view_products', 'manage_stock', 'manage_customer_credit', 'hold_products'
        ];
        $employeeRole->syncPermissions($employeePermissions);

        // Create Customer Role if it doesn't exist
        $customerRole = Role::firstOrCreate(['name' => 'Customer', 'guard_name' => 'web']);
        
        // Assign permissions to Customer role
        $customerPermissions = ['view_products', 'purchase_products'];
        $customerRole->syncPermissions($customerPermissions);

        // Create exteacher role if it doesn't exist
        $exteacherRole = Role::firstOrCreate(['name' => 'exteacher', 'guard_name' => 'web']);
        
        // Assign the grade-related permissions to exteacher role
        $exteacherPermissions = ['show_exgrades', 'edit_exgrades'];
        $exteacherRole->syncPermissions($exteacherPermissions);

        $this->command->info('Basic roles and permissions created successfully.');
    }
}

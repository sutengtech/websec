<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class UpdatePermissionDisplayNamesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define permission display names mapping
        $permissionDisplayNames = [
            'add_products' => 'Add Products',
            'edit_products' => 'Edit Products',
            'delete_products' => 'Delete Products',
            'show_users' => 'View Users',
            'edit_users' => 'Edit User Details',
            'delete_users' => 'Delete Users',
            'admin_users' => 'Administer Users',
            'view_products' => 'View Products',
            'purchase_products' => 'Purchase Products',
            'view_own_profile' => 'View Own Profile',
            'manage_stock' => 'Manage Product Inventory',
            'manage_customer_credit' => 'Manage Customer Credit Balance',
        ];

        // Update each permission's display_name
        foreach ($permissionDisplayNames as $permissionName => $displayName) {
            $permission = Permission::where('name', $permissionName)->first();
            
            if ($permission) {
                $permission->display_name = $displayName;
                $permission->save();
                $this->command->info("Updated display name for '{$permissionName}' to '{$displayName}'");
            } else {
                $this->command->warn("Permission '{$permissionName}' not found");
            }
        }

        $this->command->info('Permission display names updated successfully.');
    }
}

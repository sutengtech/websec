<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class CleanupPermissionsSeeder extends Seeder
{
    /**
     * Clean up redundant permissions.
     */
    public function run(): void
    {
        // List of redundant permissions to remove
        $redundantPermissions = [
            'Admin', // This is a role, not a permission
            'manage all credit', // Use manage_customer_credit instead
            'manage employees', // Not used in code
            'manage products', // Too general, using specific permissions
            'manage users', // Too general, using specific permissions  
            'view all customers', // Use show_users instead
            'view customers', // Use show_users instead
            'view own customers', // Use show_users + role check instead
            'view own purchases', // Using routes instead of permissions
            'purchase products', // Using purchase_products (with underscore) instead
        ];
        
        // Permissions that should be in the system (from RoleAndPermissionSeeder)
        $validPermissions = [
            'add_products',
            'edit_products',
            'delete_products',
            'show_users',
            'edit_users',
            'delete_users',
            'admin_users',
            'view_products',
            'purchase_products',
            'view_own_profile',
            'manage_stock',
            'manage_customer_credit',
        ];
        
        // Count before cleanup
        $totalBefore = Permission::count();
        $this->command->info("Total permissions before cleanup: {$totalBefore}");
        
        // Delete redundant permissions
        $deleted = 0;
        foreach ($redundantPermissions as $permission) {
            $count = Permission::where('name', $permission)->delete();
            $deleted += $count;
            if ($count > 0) {
                $this->command->info("Deleted redundant permission: {$permission}");
            }
        }
        
        // Make sure all valid permissions exist
        foreach ($validPermissions as $permissionName) {
            $permission = Permission::firstOrCreate(
                ['name' => $permissionName, 'guard_name' => 'web'],
                ['display_name' => str_replace('_', ' ', ucfirst($permissionName))]
            );
            
            // If display_name is null, set it
            if (!$permission->display_name) {
                $permission->display_name = str_replace('_', ' ', ucfirst($permissionName));
                $permission->save();
                $this->command->info("Updated display name for: {$permissionName}");
            }
        }
        
        // Count after cleanup
        $totalAfter = Permission::count();
        $this->command->info("Total permissions after cleanup: {$totalAfter}");
        $this->command->info("Removed {$deleted} redundant permissions");
    }
} 
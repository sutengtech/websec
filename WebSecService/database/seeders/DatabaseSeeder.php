<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        
        // Run the RoleAndPermissionSeeder
        $this->call(RoleAndPermissionSeeder::class);
        
        // Run the permission display name updater
        $this->call(UpdatePermissionDisplayNamesSeeder::class);
        
        // Clean up redundant permissions
        $this->call(CleanupPermissionsSeeder::class);
    }
}

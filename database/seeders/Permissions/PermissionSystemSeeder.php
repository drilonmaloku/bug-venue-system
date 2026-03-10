<?php

namespace Database\Seeders\Permissions;

use Illuminate\Database\Seeder;

class PermissionSystemSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('============================================');
        $this->command->info('  Initializing Permission System');
        $this->command->info('============================================');
        
        // Run in specific order
        $this->call([
            RolesSeeder::class,
            PermissionsSeeder::class,
            RolePermissionTemplateSeeder::class,
            UserPermissionSeeder::class,
        ]);
        
        $this->command->info('');
        $this->command->info('============================================');
        $this->command->info('  Permission System Ready!');
        $this->command->info('============================================');
    }
}

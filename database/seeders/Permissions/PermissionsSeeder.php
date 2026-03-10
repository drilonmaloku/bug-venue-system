<?php

namespace Database\Seeders\Permissions;

use App\Services\Permissions\PermissionRegistry;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Creating permissions...');
        
        $permissions = PermissionRegistry::allPermissions();
        $modules = PermissionRegistry::permissionsByModule();
        
        foreach ($modules as $module => $modulePermissions) {
            $this->command->info("  Module: {$module}");
            
            foreach ($modulePermissions as $permissionName => $meta) {
                Permission::firstOrCreate(
                    ['name' => $permissionName],
                    [
                        'name' => $permissionName,
                        'guard_name' => 'web',
                    ]
                );
                
                $this->command->info("    ✓ {$permissionName}");
            }
        }
        
        $this->command->info("Total permissions created: " . count($permissions));
    }
}

<?php

namespace Database\Seeders\Permissions;

use App\Services\Permissions\PermissionRegistry;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Creating roles...');
        
        foreach (PermissionRegistry::ROLES as $key => $roleData) {
            Role::firstOrCreate(
                ['name' => $roleData['name']],
                [
                    'name' => $roleData['name'],
                    'guard_name' => 'web',
                ]
            );
            
            $this->command->info("  ✓ Role: {$roleData['display_name']}");
        }
        
        $this->command->info('Roles created successfully!');
    }
}

<?php

namespace Database\Seeders\Permissions;

use App\Models\RolePermissionTemplate;
use App\Services\Permissions\PermissionRegistry;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Creating role permission templates...');
        
        // Clear existing templates
        RolePermissionTemplate::truncate();
        
        foreach (PermissionRegistry::ROLE_PERMISSION_TEMPLATES as $roleName => $template) {
            $role = Role::where('name', $roleName)->first();
            
            if (!$role) {
                $this->command->warn("  Role not found: {$roleName}");
                continue;
            }
            
            // Handle wildcard
            if (isset($template['wildcard']) && $template['wildcard'] === true) {
                $this->command->info("  {$roleName}: All permissions (wildcard)");
                continue;
            }
            
            $permissionIds = Permission::whereIn('name', $template)
                ->pluck('id')
                ->toArray();
            
            foreach ($permissionIds as $permissionId) {
                RolePermissionTemplate::create([
                    'role_id' => $role->id,
                    'permission_id' => $permissionId,
                ]);
            }
            
            $this->command->info("  {$roleName}: " . count($permissionIds) . " permissions");
        }
        
        $this->command->info('Role permission templates created successfully!');
    }
}

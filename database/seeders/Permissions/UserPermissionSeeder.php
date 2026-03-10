<?php

namespace Database\Seeders\Permissions;

use App\Models\User;
use App\Services\Permissions\PermissionManager;
use Illuminate\Database\Seeder;

class UserPermissionSeeder extends Seeder
{
    public function __construct(
        private PermissionManager $permissionManager
    ) {}

    public function run(): void
    {
        $this->command->info('Assigning permissions to existing users...');
        
        $users = User::with('roles')->get();
        
        foreach ($users as $user) {
            $role = $user->roles->first();
            
            if (!$role) {
                $this->command->warn("  User {$user->email} has no role, skipping");
                continue;
            }
            
            // Only assign if user has no direct permissions yet
            if ($user->permissions->isEmpty()) {
                $this->permissionManager->assignRoleTemplate(
                    $user, 
                    $role->name, 
                    1 // system user ID for seeding
                );
                
                $this->command->info("  ✓ {$user->email} -> {$role->name}");
            } else {
                $this->command->info("  ~ {$user->email} already has permissions (skipped)");
            }
        }
        
        $this->command->info('User permissions assigned successfully!');
    }
}

<?php

namespace App\Services\Permissions;

use App\Models\PermissionAuditLog;
use App\Models\User;
use App\Models\UserPermissionException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionManager
{
    /**
     * Assign role template permissions to user
     * Called when creating a new user
     */
    public function assignRoleTemplate(User $user, string $roleName, ?int $performedBy = null): void
    {
        DB::transaction(function () use ($user, $roleName, $performedBy) {
            // Get default permissions for role
            $permissions = PermissionRegistry::getRoleTemplate($roleName);
            
            // Convert to permission IDs
            $permissionIds = Permission::whereIn('name', $permissions)
                ->pluck('id')
                ->toArray();
            
            // Sync permissions directly to user (not via role)
            $user->syncPermissions($permissionIds);
            
            // Assign role (for reference/template tracking)
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                $user->syncRoles([$role->id]);
            }
            
            // Log the assignment
            PermissionAuditLog::create([
                'user_id' => $user->id,
                'action' => 'template_assigned',
                'permissions_snapshot' => [
                    'role' => $roleName,
                    'permissions' => $permissions,
                ],
                'performed_by' => $performedBy ?? $user->id,
                'performed_at' => now(),
            ]);
        });
    }

    /**
     * Sync user permissions from role template
     * Resets user permissions to match their current role template
     */
    public function syncWithRoleTemplate(User $user, ?int $performedBy = null): void
    {
        DB::transaction(function () use ($user, $performedBy) {
            $role = $user->roles->first();
            
            if (!$role) {
                throw new \InvalidArgumentException('User has no role assigned');
            }
            
            // Get exceptions before sync
            $exceptions = $this->getUserExceptions($user);
            
            // Get template permissions
            $templatePermissions = PermissionRegistry::getRoleTemplate($role->name);
            
            // Apply exceptions
            foreach ($exceptions as $exception) {
                if ($exception->type === 'added') {
                    if (!in_array($exception->permission->name, $templatePermissions)) {
                        $templatePermissions[] = $exception->permission->name;
                    }
                } elseif ($exception->type === 'removed') {
                    $templatePermissions = array_diff($templatePermissions, [$exception->permission->name]);
                }
            }
            
            // Convert to IDs and sync
            $permissionIds = Permission::whereIn('name', $templatePermissions)
                ->pluck('id')
                ->toArray();
            
            $user->syncPermissions($permissionIds);
            
            // Log
            PermissionAuditLog::create([
                'user_id' => $user->id,
                'action' => 'synced_with_template',
                'permissions_snapshot' => [
                    'role' => $role->name,
                    'permissions' => $templatePermissions,
                    'exceptions_applied' => $exceptions->count(),
                ],
                'performed_by' => $performedBy ?? $user->id,
                'performed_at' => now(),
            ]);
        });
    }

    /**
     * Grant single permission to user
     */
    public function grantPermission(
        User $user, 
        string $permission, 
        ?string $reason = null, 
        ?int $grantedBy = null
    ): void {
        DB::transaction(function () use ($user, $permission, $reason, $grantedBy) {
            $permissionModel = Permission::where('name', $permission)->firstOrFail();
            
            // Grant permission
            $user->givePermissionTo($permissionModel);
            
            // Record as exception
            UserPermissionException::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'permission_id' => $permissionModel->id,
                ],
                [
                    'type' => 'added',
                    'reason' => $reason,
                    'granted_by' => $grantedBy ?? auth()->id(),
                ]
            );
            
            // Log
            PermissionAuditLog::create([
                'user_id' => $user->id,
                'action' => 'granted',
                'permission_id' => $permissionModel->id,
                'performed_by' => $grantedBy ?? auth()->id() ?? $user->id,
                'reason' => $reason,
                'performed_at' => now(),
            ]);
        });
    }

    /**
     * Revoke single permission from user
     */
    public function revokePermission(
        User $user, 
        string $permission, 
        ?string $reason = null, 
        ?int $revokedBy = null
    ): void {
        DB::transaction(function () use ($user, $permission, $reason, $revokedBy) {
            $permissionModel = Permission::where('name', $permission)->firstOrFail();
            
            // Revoke permission
            $user->revokePermissionTo($permissionModel);
            
            // Record as exception
            UserPermissionException::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'permission_id' => $permissionModel->id,
                ],
                [
                    'type' => 'removed',
                    'reason' => $reason,
                    'granted_by' => $revokedBy ?? auth()->id(),
                ]
            );
            
            // Log
            PermissionAuditLog::create([
                'user_id' => $user->id,
                'action' => 'revoked',
                'permission_id' => $permissionModel->id,
                'performed_by' => $revokedBy ?? auth()->id() ?? $user->id,
                'reason' => $reason,
                'performed_at' => now(),
            ]);
        });
    }

    /**
     * Bulk update user permissions
     */
    public function syncPermissions(
        User $user, 
        array $permissions, 
        ?string $reason = null, 
        ?int $performedBy = null
    ): void {
        DB::transaction(function () use ($user, $permissions, $reason, $performedBy) {
            $permissionIds = Permission::whereIn('name', $permissions)
                ->pluck('id')
                ->toArray();
            
            $user->syncPermissions($permissionIds);
            
            // Clear exceptions (manual override)
            UserPermissionException::where('user_id', $user->id)->delete();
            
            // Log
            PermissionAuditLog::create([
                'user_id' => $user->id,
                'action' => 'synced',
                'permissions_snapshot' => ['permissions' => $permissions],
                'performed_by' => $performedBy ?? auth()->id() ?? $user->id,
                'reason' => $reason ?? 'Bulk permission update',
                'performed_at' => now(),
            ]);
        });
    }

    /**
     * Get user permission exceptions
     */
    public function getUserExceptions(User $user): Collection
    {
        return UserPermissionException::with('permission')
            ->where('user_id', $user->id)
            ->get();
    }

    /**
     * Compare user permissions with their role template
     */
    public function compareWithTemplate(User $user): array
    {
        $role = $user->roles->first();
        
        if (!$role) {
            return [
                'has_role' => false,
                'template_permissions' => [],
                'user_permissions' => $user->permissions->pluck('name')->toArray(),
                'exceptions' => [],
            ];
        }
        
        $templatePermissions = PermissionRegistry::getRoleTemplate($role->name);
        $userPermissions = $user->permissions->pluck('name')->toArray();
        
        return [
            'has_role' => true,
            'role' => $role->name,
            'template_permissions' => $templatePermissions,
            'user_permissions' => $userPermissions,
            'missing_from_template' => array_diff($userPermissions, $templatePermissions),
            'missing_in_user' => array_diff($templatePermissions, $userPermissions),
            'exceptions' => $this->getUserExceptions($user)->toArray(),
        ];
    }

    /**
     * Get audit log for user
     */
    public function getAuditLog(User $user, int $limit = 50): Collection
    {
        return PermissionAuditLog::with(['permission', 'performer'])
            ->where('user_id', $user->id)
            ->orderBy('performed_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Check if user has permission (with scope awareness)
     */
    public function hasPermission(User $user, string $permission): bool
    {
        // Direct permission check
        if ($user->hasDirectPermission($permission)) {
            return true;
        }
        
        // Check for wildcard system-admin
        if ($user->hasRole('system-admin')) {
            return true;
        }
        
        // Check for broader scope permissions
        $parts = explode('.', $permission);
        if (count($parts) >= 3) {
            $module = $parts[0];
            $action = $parts[1];
            $scope = $parts[2];
            
            // If checking for .own, also check .location and .all
            if ($scope === 'own') {
                if ($user->hasDirectPermission("{$module}.{$action}.location") ||
                    $user->hasDirectPermission("{$module}.{$action}.all")) {
                    return true;
                }
            }
            
            // If checking for .location, also check .all
            if ($scope === 'location') {
                if ($user->hasDirectPermission("{$module}.{$action}.all")) {
                    return true;
                }
            }
        }
        
        return false;
    }
}

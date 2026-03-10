<?php

namespace App\Services\Permissions;

class PermissionRegistry
{
    /**
     * All defined permissions organized by module
     */
    public const PERMISSIONS = [
        // ==================== RESERVATIONS ====================
        'reservations' => [
            'view' => [
                'reservations.view.all' => [
                    'description' => 'View all reservations across all locations',
                    'scope' => 'all',
                ],
                'reservations.view.location' => [
                    'description' => 'View all reservations in assigned locations',
                    'scope' => 'location',
                ],
                'reservations.view.own' => [
                    'description' => 'View only own reservations',
                    'scope' => 'own',
                ],
                'reservations.view.assigned' => [
                    'description' => 'View reservations assigned to user',
                    'scope' => 'assigned',
                ],
            ],
            'create' => [
                'reservations.create' => [
                    'description' => 'Create new reservations',
                ],
            ],
            'update' => [
                'reservations.update.all' => [
                    'description' => 'Edit any reservation',
                    'scope' => 'all',
                ],
                'reservations.update.own' => [
                    'description' => 'Edit only own reservations',
                    'scope' => 'own',
                ],
                'reservations.update.assigned' => [
                    'description' => 'Edit assigned reservations',
                    'scope' => 'assigned',
                ],
            ],
            'delete' => [
                'reservations.delete.all' => [
                    'description' => 'Delete any reservation',
                    'scope' => 'all',
                ],
                'reservations.delete.own' => [
                    'description' => 'Delete own reservations',
                    'scope' => 'own',
                ],
            ],
            'manage' => [
                'reservations.manage.status' => [
                    'description' => 'Change reservation status',
                ],
                'reservations.manage.assignment' => [
                    'description' => 'Assign reservations to staff',
                ],
                'reservations.manage.calendar' => [
                    'description' => 'Manage calendar view and conflicts',
                ],
            ],
            'export' => [
                'reservations.export' => [
                    'description' => 'Export reservation data',
                ],
            ],
        ],

        // ==================== CLIENTS ====================
        'clients' => [
            'view' => [
                'clients.view.all' => [
                    'description' => 'View all client records',
                    'scope' => 'all',
                ],
                'clients.view.location' => [
                    'description' => 'View clients from assigned locations',
                    'scope' => 'location',
                ],
                'clients.view.own' => [
                    'description' => 'View own clients',
                    'scope' => 'own',
                ],
            ],
            'create' => [
                'clients.create' => [
                    'description' => 'Create new client records',
                ],
            ],
            'update' => [
                'clients.update.all' => [
                    'description' => 'Edit any client record',
                    'scope' => 'all',
                ],
                'clients.update.own' => [
                    'description' => 'Edit own clients',
                    'scope' => 'own',
                ],
            ],
            'delete' => [
                'clients.delete.all' => [
                    'description' => 'Delete any client',
                    'scope' => 'all',
                ],
                'clients.delete.own' => [
                    'description' => 'Delete own clients',
                    'scope' => 'own',
                ],
            ],
            'manage' => [
                'clients.manage.contacts' => [
                    'description' => 'Manage client contacts',
                ],
                'clients.manage.history' => [
                    'description' => 'View full client history',
                ],
                'clients.manage.segmentation' => [
                    'description' => 'Manage client categories/segments',
                ],
            ],
            'export' => [
                'clients.export' => [
                    'description' => 'Export client data',
                ],
            ],
        ],

        // ==================== PAYMENTS ====================
        'payments' => [
            'view' => [
                'payments.view.all' => [
                    'description' => 'View all payments',
                    'scope' => 'all',
                ],
                'payments.view.location' => [
                    'description' => 'View payments for location',
                    'scope' => 'location',
                ],
                'payments.view.own' => [
                    'description' => 'View payments for own reservations',
                    'scope' => 'own',
                ],
            ],
            'create' => [
                'payments.create' => [
                    'description' => 'Record new payments',
                ],
            ],
            'update' => [
                'payments.update.all' => [
                    'description' => 'Edit any payment record',
                    'scope' => 'all',
                ],
                'payments.update.own' => [
                    'description' => 'Edit own payment records',
                    'scope' => 'own',
                ],
            ],
            'delete' => [
                'payments.delete.all' => [
                    'description' => 'Delete any payment record',
                    'scope' => 'all',
                ],
            ],
            'manage' => [
                'payments.manage.refund' => [
                    'description' => 'Process refunds',
                ],
                'payments.manage.partial' => [
                    'description' => 'Process partial payments',
                ],
                'payments.manage.methods' => [
                    'description' => 'Manage payment methods',
                ],
            ],
            'approve' => [
                'payments.approve.large' => [
                    'description' => 'Approve payments above threshold',
                ],
                'payments.approve.refund' => [
                    'description' => 'Approve refund requests',
                ],
            ],
        ],

        // ==================== VENUES ====================
        'venues' => [
            'view' => [
                'venues.view.all' => [
                    'description' => 'View all venues',
                    'scope' => 'all',
                ],
                'venues.view.location' => [
                    'description' => 'View venues in assigned locations',
                    'scope' => 'location',
                ],
            ],
            'create' => [
                'venues.create' => [
                    'description' => 'Create new venues',
                ],
            ],
            'update' => [
                'venues.update.all' => [
                    'description' => 'Edit any venue',
                    'scope' => 'all',
                ],
                'venues.update.location' => [
                    'description' => 'Edit venues in assigned locations',
                    'scope' => 'location',
                ],
            ],
            'delete' => [
                'venues.delete.all' => [
                    'description' => 'Delete venues',
                    'scope' => 'all',
                ],
            ],
            'manage' => [
                'venues.manage.availability' => [
                    'description' => 'Manage venue availability',
                ],
                'venues.manage.pricing' => [
                    'description' => 'Manage venue pricing',
                ],
                'venues.manage.layouts' => [
                    'description' => 'Manage venue layouts/configurations',
                ],
            ],
        ],

        // ==================== MENUS ====================
        'menus' => [
            'view' => [
                'menus.view.all' => [
                    'description' => 'View all menus',
                    'scope' => 'all',
                ],
                'menus.view.location' => [
                    'description' => 'View location menus',
                    'scope' => 'location',
                ],
            ],
            'create' => [
                'menus.create' => [
                    'description' => 'Create new menus',
                ],
            ],
            'update' => [
                'menus.update.all' => [
                    'description' => 'Edit any menu',
                    'scope' => 'all',
                ],
                'menus.update.location' => [
                    'description' => 'Edit location menus',
                    'scope' => 'location',
                ],
            ],
            'delete' => [
                'menus.delete.all' => [
                    'description' => 'Delete menus',
                    'scope' => 'all',
                ],
            ],
            'manage' => [
                'menus.manage.items' => [
                    'description' => 'Manage menu items',
                ],
                'menus.manage.pricing' => [
                    'description' => 'Manage menu pricing',
                ],
                'menus.manage.categories' => [
                    'description' => 'Manage menu categories',
                ],
            ],
        ],

        // ==================== DECORS ====================
        'decors' => [
            'view' => [
                'decors.view.all' => [
                    'description' => 'View all decors',
                    'scope' => 'all',
                ],
                'decors.view.location' => [
                    'description' => 'View location decors',
                    'scope' => 'location',
                ],
            ],
            'create' => [
                'decors.create' => [
                    'description' => 'Create new decors',
                ],
            ],
            'update' => [
                'decors.update.all' => [
                    'description' => 'Edit any decor',
                    'scope' => 'all',
                ],
                'decors.update.location' => [
                    'description' => 'Edit location decors',
                    'scope' => 'location',
                ],
            ],
            'delete' => [
                'decors.delete.all' => [
                    'description' => 'Delete decors',
                    'scope' => 'all',
                ],
            ],
            'manage' => [
                'decors.manage.inventory' => [
                    'description' => 'Manage decor inventory',
                ],
                'decors.manage.pricing' => [
                    'description' => 'Manage decor pricing',
                ],
            ],
        ],

        // ==================== EXPENSES ====================
        'expenses' => [
            'view' => [
                'expenses.view.all' => [
                    'description' => 'View all expenses',
                    'scope' => 'all',
                ],
                'expenses.view.location' => [
                    'description' => 'View location expenses',
                    'scope' => 'location',
                ],
                'expenses.view.own' => [
                    'description' => 'View own expense reports',
                    'scope' => 'own',
                ],
            ],
            'create' => [
                'expenses.create' => [
                    'description' => 'Create expense records',
                ],
            ],
            'update' => [
                'expenses.update.all' => [
                    'description' => 'Edit any expense',
                    'scope' => 'all',
                ],
                'expenses.update.own' => [
                    'description' => 'Edit own expenses',
                    'scope' => 'own',
                ],
            ],
            'delete' => [
                'expenses.delete.all' => [
                    'description' => 'Delete expenses',
                    'scope' => 'all',
                ],
            ],
            'approve' => [
                'expenses.approve' => [
                    'description' => 'Approve expense reports',
                ],
            ],
        ],

        // ==================== USERS ====================
        'users' => [
            'view' => [
                'users.view.all' => [
                    'description' => 'View all users',
                    'scope' => 'all',
                ],
                'users.view.location' => [
                    'description' => 'View users in assigned locations',
                    'scope' => 'location',
                ],
            ],
            'create' => [
                'users.create' => [
                    'description' => 'Create new users',
                ],
            ],
            'update' => [
                'users.update.all' => [
                    'description' => 'Edit any user',
                    'scope' => 'all',
                ],
                'users.update.location' => [
                    'description' => 'Edit users in assigned locations',
                    'scope' => 'location',
                ],
                'users.update.own' => [
                    'description' => 'Edit own profile',
                    'scope' => 'own',
                ],
            ],
            'delete' => [
                'users.delete.all' => [
                    'description' => 'Delete users',
                    'scope' => 'all',
                ],
            ],
            'manage' => [
                'users.manage.permissions' => [
                    'description' => 'Manage user permissions',
                ],
                'users.manage.roles' => [
                    'description' => 'Assign/modify user roles',
                ],
                'users.manage.status' => [
                    'description' => 'Activate/deactivate users',
                ],
                'users.manage.locations' => [
                    'description' => 'Assign user locations',
                ],
            ],
        ],

        // ==================== REPORTS ====================
        'reports' => [
            'view' => [
                'reports.view.all' => [
                    'description' => 'View all reports',
                    'scope' => 'all',
                ],
                'reports.view.location' => [
                    'description' => 'View location reports',
                    'scope' => 'location',
                ],
                'reports.view.own' => [
                    'description' => 'View own reports',
                    'scope' => 'own',
                ],
            ],
            'create' => [
                'reports.create' => [
                    'description' => 'Create custom reports',
                ],
            ],
            'manage' => [
                'reports.manage.scheduled' => [
                    'description' => 'Manage scheduled reports',
                ],
                'reports.manage.dashboard' => [
                    'description' => 'Customize dashboards',
                ],
            ],
            'export' => [
                'reports.export' => [
                    'description' => 'Export reports',
                ],
            ],
            'financial' => [
                'reports.financial.revenue' => [
                    'description' => 'View revenue reports',
                ],
                'reports.financial.profit' => [
                    'description' => 'View profit/loss reports',
                ],
                'reports.financial.forecast' => [
                    'description' => 'View forecast reports',
                ],
            ],
        ],

        // ==================== SETTINGS ====================
        'settings' => [
            'view' => [
                'settings.view' => [
                    'description' => 'View settings',
                ],
            ],
            'manage' => [
                'settings.manage.general' => [
                    'description' => 'Manage general settings',
                ],
                'settings.manage.locations' => [
                    'description' => 'Manage location settings',
                ],
                'settings.manage.notifications' => [
                    'description' => 'Manage notification settings',
                ],
                'settings.manage.integrations' => [
                    'description' => 'Manage system integrations',
                ],
                'settings.manage.security' => [
                    'description' => 'Manage security settings',
                ],
                'settings.manage.backup' => [
                    'description' => 'Manage backups',
                ],
            ],
        ],
    ];

    /**
     * Role definitions with default permission templates
     */
    public const ROLES = [
        'system-admin' => [
            'name' => 'system-admin',
            'display_name' => 'System Administrator',
            'description' => 'Full system access across all locations',
            'level' => 100,
        ],
        'location-owner' => [
            'name' => 'location-owner',
            'display_name' => 'Location Owner',
            'description' => 'Full access to assigned locations',
            'level' => 90,
        ],
        'location-admin' => [
            'name' => 'location-admin',
            'display_name' => 'Location Administrator',
            'description' => 'Administrative access to assigned locations',
            'level' => 80,
        ],
        'manager' => [
            'name' => 'manager',
            'display_name' => 'Manager',
            'description' => 'Management access with approval rights',
            'level' => 70,
        ],
        'staff' => [
            'name' => 'staff',
            'display_name' => 'Staff Member',
            'description' => 'Standard operational access',
            'level' => 50,
        ],
        'kitchen' => [
            'name' => 'kitchen',
            'display_name' => 'Kitchen Staff',
            'description' => 'Kitchen-specific access',
            'level' => 40,
        ],
        'readonly' => [
            'name' => 'readonly',
            'display_name' => 'Read Only',
            'description' => 'View-only access',
            'level' => 10,
        ],
    ];

    /**
     * Default permissions mapped to roles (TEMPLATE DEFINITIONS)
     */
    public const ROLE_PERMISSION_TEMPLATES = [
        'system-admin' => [
            // Full access - all permissions
            'wildcard' => true,
        ],
        'location-owner' => [
            'reservations.view.all',
            'reservations.create',
            'reservations.update.all',
            'reservations.delete.all',
            'reservations.manage.status',
            'reservations.manage.assignment',
            'reservations.manage.calendar',
            'reservations.export',
            'clients.view.all',
            'clients.create',
            'clients.update.all',
            'clients.delete.all',
            'clients.manage.contacts',
            'clients.manage.history',
            'clients.export',
            'payments.view.all',
            'payments.create',
            'payments.update.all',
            'payments.manage.refund',
            'payments.manage.partial',
            'payments.approve.large',
            'payments.approve.refund',
            'venues.view.all',
            'venues.create',
            'venues.update.all',
            'venues.delete.all',
            'venues.manage.availability',
            'venues.manage.pricing',
            'venues.manage.layouts',
            'menus.view.all',
            'menus.create',
            'menus.update.all',
            'menus.delete.all',
            'menus.manage.items',
            'menus.manage.pricing',
            'decors.view.all',
            'decors.create',
            'decors.update.all',
            'decors.delete.all',
            'decors.manage.inventory',
            'expenses.view.all',
            'expenses.create',
            'expenses.update.all',
            'expenses.approve',
            'users.view.all',
            'users.create',
            'users.update.all',
            'users.update.location',
            'users.manage.permissions',
            'users.manage.roles',
            'users.manage.status',
            'users.manage.locations',
            'reports.view.all',
            'reports.create',
            'reports.manage.scheduled',
            'reports.manage.dashboard',
            'reports.export',
            'reports.financial.revenue',
            'reports.financial.profit',
            'reports.financial.forecast',
            'settings.view',
            'settings.manage.general',
            'settings.manage.locations',
            'settings.manage.notifications',
        ],
        'location-admin' => [
            'reservations.view.all',
            'reservations.create',
            'reservations.update.all',
            'reservations.delete.own',
            'reservations.manage.status',
            'reservations.manage.assignment',
            'reservations.manage.calendar',
            'reservations.export',
            'clients.view.all',
            'clients.create',
            'clients.update.all',
            'clients.delete.own',
            'clients.manage.contacts',
            'clients.export',
            'payments.view.all',
            'payments.create',
            'payments.update.own',
            'payments.manage.partial',
            'payments.approve.refund',
            'venues.view.all',
            'venues.update.location',
            'venues.manage.availability',
            'menus.view.all',
            'menus.update.location',
            'decors.view.all',
            'decors.update.location',
            'expenses.view.all',
            'expenses.create',
            'expenses.update.own',
            'users.view.location',
            'users.create',
            'users.update.location',
            'users.manage.permissions',
            'users.manage.status',
            'reports.view.all',
            'reports.create',
            'reports.export',
            'reports.financial.revenue',
            'settings.view',
            'settings.manage.locations',
            'settings.manage.notifications',
        ],
        'manager' => [
            'reservations.view.all',
            'reservations.create',
            'reservations.update.assigned',
            'reservations.manage.status',
            'reservations.manage.calendar',
            'clients.view.all',
            'clients.create',
            'clients.update.own',
            'clients.manage.contacts',
            'payments.view.location',
            'payments.create',
            'payments.manage.partial',
            'payments.approve.refund',
            'venues.view.all',
            'venues.manage.availability',
            'menus.view.all',
            'decors.view.all',
            'expenses.view.own',
            'expenses.create',
            'users.view.location',
            'reports.view.location',
            'reports.create',
            'reports.export',
        ],
        'staff' => [
            'reservations.view.assigned',
            'reservations.create',
            'reservations.update.own',
            'clients.view.own',
            'clients.create',
            'clients.update.own',
            'payments.view.own',
            'venues.view.location',
            'menus.view.location',
            'decors.view.location',
            'expenses.view.own',
            'expenses.create',
            'users.update.own',
            'reports.view.own',
        ],
        'kitchen' => [
            'reservations.view.location',
            'reservations.manage.status',
            'menus.view.location',
            'menus.manage.items',
        ],
        'readonly' => [
            'reservations.view.location',
            'clients.view.location',
            'payments.view.location',
            'venues.view.location',
            'menus.view.location',
            'decors.view.location',
            'reports.view.location',
        ],
    ];

    /**
     * Get all permission names as flat array
     */
    public static function allPermissions(): array
    {
        $permissions = [];
        
        foreach (self::PERMISSIONS as $module => $actions) {
            foreach ($actions as $action => $definitions) {
                foreach ($definitions as $permission => $meta) {
                    $permissions[] = $permission;
                }
            }
        }
        
        return $permissions;
    }

    /**
     * Get permissions grouped by module
     */
    public static function permissionsByModule(): array
    {
        $grouped = [];
        
        foreach (self::PERMISSIONS as $module => $actions) {
            $grouped[$module] = [];
            foreach ($actions as $action => $definitions) {
                foreach ($definitions as $permission => $meta) {
                    $grouped[$module][$permission] = $meta;
                }
            }
        }
        
        return $grouped;
    }

    /**
     * Get default permissions for a role
     */
    public static function getRoleTemplate(string $roleName): array
    {
        $template = self::ROLE_PERMISSION_TEMPLATES[$roleName] ?? [];
        
        // Handle wildcard (system-admin gets everything)
        if (isset($template['wildcard']) && $template['wildcard'] === true) {
            return self::allPermissions();
        }
        
        return $template;
    }

    /**
     * Check if permission exists
     */
    public static function hasPermission(string $permission): bool
    {
        return in_array($permission, self::allPermissions());
    }

    /**
     * Get permission metadata
     */
    public static function getPermissionMeta(string $permission): ?array
    {
        foreach (self::PERMISSIONS as $module => $actions) {
            foreach ($actions as $action => $definitions) {
                if (isset($definitions[$permission])) {
                    return array_merge(
                        $definitions[$permission],
                        ['module' => $module, 'action' => $action]
                    );
                }
            }
        }
        
        return null;
    }
}

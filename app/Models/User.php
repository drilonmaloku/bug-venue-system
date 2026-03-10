<?php

namespace App\Models;

use App\Modules\Expenses\Models\Expense;
use App\Modules\Location\Models\Location;
use App\Modules\Users\Models\LocationUser;
use App\Modules\Users\Models\UserSettings;
use App\Services\Permissions\PermissionManager;
use App\Services\Permissions\PermissionRegistry;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable
{ 
    use
        HasFactory,
        Notifiable,
        HasRoles,
        SoftDeletes;


    const ROLE_ADMIN = "admin";
    const ROLE_SUPERAMDIN = "super-admin";
    const ROLE_MANAGER = "manager";
    const SYSTEM_ADMIN = "system-admin";
    const ROLE_STAFF = "staff";
    const ROLE_KITCHEN = "kitchen";




    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'language',
        'username',
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'preferences' => 'array',
    ];


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public static function defaultNotificationPreferences()
    {
        return [
                'coment-added' => true,
                'comment-deleted' => true,
                'discount-added' => true,
                'discount-updated' => true,
                'discount-deleted' => true,
                'invoices-added' => true,
                'invoices-deleted' => true,
                'reservation-added' => true,
                'reservation-deleted' => true,
                'reservation-updated' => true,
                'staff-added' => true,
                'staff-deleted' => true,
                'payment-added' => true,
                'payment-updated' => true,
                'payment-deleted' => true,
                'expenses-added' => true,
                'expenses-updated' => true,
                'expenses-deleted' => true,
                'menu-added' => true,
                'menu-updated' => true,
                'menu-deleted' => true,
                'client-updated' => true,
                'venue-added' => true,
                'venue-updated' => true,
                'venue-deleted' => true,
                'reservation-updated-status' => true,
        ];
    }

    public function getNameAttribute(){
        return $this->first_name .' '.$this->last_name;
    }


    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function locationUsers()
    {
        return $this->hasMany(LocationUser::class, 'user_id');
    }

    public function locations()
    {
        return $this->belongsToMany(Location::class, 'locations_users', 'user_id', 'location_id');
    }


    public function isLocationEnabled()
    {
        if(auth()->user()->hasRole('system-admin')) {
            return true;
        }
        $locationId = $this->getCurrentLocationId();

        if (!$locationId) {
            return false; 
        }


        try {
            $location = Location::findOrFail($locationId);
            return $location->deactivated_at === null; // Assuming 'disabled_at' is your column name
        } catch (ModelNotFoundException $e) {
            return false; // Location not found
        }
    }

    /**
     * Get the current location for the user.
     *
     * @return id|null
     */
    public function getCurrentLocation()
    {
        $locationUser = $this->locationUsers->first();
        return $locationUser ? $locationUser->location : null;
    }

    /**
     * Get the current location for the user.
     *
     * @return id|null
     */
    public function getCurrentLocationId()
    {
        $locationUser = $this->locationUsers->first();
        return $locationUser ? $locationUser->location_id : null;
    }

    public function getCurrentLocationSlug()
    {
        $locationUser = $this->locationUsers->first();
        return $locationUser ? Location::find($locationUser->location_id)->slug : null;
    }
    
    public function notificationPreferences()
    {
        return $this->hasOne(NotificationPreference::class);
    }


    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function userSettings()
    {
        return $this->hasOne(UserSettings::class);
    }

    /**
     * Check if user account is active
     */
    public function isActive(): bool
    {
        return $this->deleted_at === null;
    }

    /**
     * Get the reservations for the user (as manager).
     */
    public function reservations()
    {
        return $this->hasMany(\App\Modules\Reservations\Models\Reservation::class, 'manager_id');
    }

    // ==================== PERMISSION SYSTEM METHODS ====================

    /**
     * Check if user can perform action on resource
     * Scope-aware permission check
     */
    public function canAction(string $resource, string $action, ?string $scope = null, $resourceInstance = null): bool
    {
        $permissionManager = app(PermissionManager::class);
        
        // Build permission string
        if ($scope) {
            $permission = "{$resource}.{$action}.{$scope}";
        } else {
            $permission = "{$resource}.{$action}";
        }
        
        // Check base permission
        if (!$permissionManager->hasPermission($this, $permission)) {
            return false;
        }
        
        // Additional ownership/resource checks if needed
        if ($resourceInstance && method_exists($this, 'owns')) {
            $owns = $this->owns($resourceInstance);
            
            // If scope is 'own', verify ownership
            if ($scope === 'own' && !$owns) {
                return false;
            }
            
            // If scope is 'assigned', verify assignment
            if ($scope === 'assigned' && !$this->isAssigned($resourceInstance)) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Check if user has specific permission with scope fallback
     */
    public function hasPermissionWithFallback(string $permission): bool
    {
        $permissionManager = app(PermissionManager::class);
        return $permissionManager->hasPermission($this, $permission);
    }

    /**
     * Get all effective permissions including inherited
     */
    public function effectivePermissions(): array
    {
        return $this->permissions->pluck('name')->toArray();
    }

    /**
     * Check if user has a permission from their role template
     */
    public function hasTemplatePermission(string $permission): bool
    {
        $role = $this->roles->first();
        
        if (!$role) {
            return false;
        }
        
        $templatePermissions = PermissionRegistry::getRoleTemplate($role->name);
        return in_array($permission, $templatePermissions);
    }

    /**
     * Get user's permission exceptions
     */
    public function permissionExceptions()
    {
        return $this->hasMany(UserPermissionException::class);
    }

    /**
     * Check if user has custom permissions (deviations from template)
     */
    public function hasCustomPermissions(): bool
    {
        return $this->permissionExceptions()->exists();
    }

    /**
     * Reset permissions to role template
     */
    public function resetPermissionsToTemplate(?int $performedBy = null): void
    {
        $permissionManager = app(PermissionManager::class);
        $permissionManager->syncWithRoleTemplate($this, $performedBy);
    }

    /**
     * Get permission comparison with template
     */
    public function permissionComparison(): array
    {
        $permissionManager = app(PermissionManager::class);
        return $permissionManager->compareWithTemplate($this);
    }

    // ==================== ROLE HELPERS ====================

    /**
     * Assign role and apply template permissions
     */
    public function assignRoleWithPermissions($roles, ?int $performedBy = null): void
    {
        $this->assignRole($roles);
        
        $role = is_array($roles) ? $roles[0] : $roles;
        $roleName = $role instanceof \Spatie\Permission\Models\Role ? $role->name : $role;
        
        $permissionManager = app(PermissionManager::class);
        $permissionManager->assignRoleTemplate($this, $roleName, $performedBy);
    }

    /**
     * Get role level for hierarchy checks
     */
    public function roleLevel(): int
    {
        $role = $this->roles->first();
        
        if (!$role) {
            return 0;
        }
        
        return PermissionRegistry::ROLES[$role->name]['level'] ?? 0;
    }

    /**
     * Check if user can manage another user (hierarchy check)
     */
    public function canManage(User $otherUser): bool
    {
        // Cannot manage self
        if ($this->id === $otherUser->id) {
            return false;
        }
        
        // System admin can manage everyone except other system admins
        if ($this->hasRole('system-admin')) {
            return !$otherUser->hasRole('system-admin') || $this->id < $otherUser->id;
        }
        
        // Location owner can manage users in their locations with lower role level
        if ($this->hasRole('location-owner')) {
            return $this->roleLevel() > $otherUser->roleLevel();
        }
        
        // Location admin can manage staff in same location
        if ($this->hasRole('location-admin')) {
            $thisLocationId = $this->getCurrentLocationId();
            $otherLocationId = $otherUser->getCurrentLocationId();
            
            return $thisLocationId === $otherLocationId 
                && $this->roleLevel() > $otherUser->roleLevel();
        }
        
        return false;
    }
}
    


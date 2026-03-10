<?php

namespace App\Http\Middleware;

use App\Services\Permissions\PermissionManager;
use Closure;
use Illuminate\Http\Request;

class ResourcePermission
{
    public function __construct(
        private PermissionManager $permissionManager
    ) {}

    /**
     * Check permission with scope fallback
     * Usage: 'resource.permission:reservations,view,{id}'
     * {id} will be replaced with route parameter
     */
    public function handle(Request $request, Closure $next, string $resource, string $action, string $resourceIdParam = '')
    {
        $user = $request->user();
        
        if (!$user) {
            abort(401, 'Unauthenticated');
        }
        
        // Try specific scopes in order of specificity
        $scopes = ['own', 'assigned', 'location', 'all'];
        $hasPermission = false;
        
        foreach ($scopes as $scope) {
            $permission = "{$resource}.{$action}.{$scope}";
            if ($this->permissionManager->hasPermission($user, $permission)) {
                $hasPermission = true;
                
                // Add scope to request for controller use
                $request->attributes->set('permission_scope', $scope);
                break;
            }
        }
        
        // Check non-scoped permission as fallback
        if (!$hasPermission) {
            $permission = "{$resource}.{$action}";
            $hasPermission = $this->permissionManager->hasPermission($user, $permission);
        }
        
        if (!$hasPermission) {
            abort(403, "Forbidden: Missing {$resource}.{$action} permission");
        }
        
        return $next($request);
    }
}

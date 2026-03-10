<?php

namespace App\Http\Middleware;

use App\Services\Permissions\PermissionManager;
use Closure;
use Illuminate\Http\Request;

class CheckAnyPermission
{
    public function __construct(
        private PermissionManager $permissionManager
    ) {}

    public function handle(Request $request, Closure $next, ...$permissions)
    {
        $user = $request->user();
        
        if (!$user) {
            abort(401, 'Unauthenticated');
        }
        
        foreach ($permissions as $permission) {
            if ($this->permissionManager->hasPermission($user, $permission)) {
                return $next($request);
            }
        }
        
        abort(403, 'Forbidden: Missing required permissions');
    }
}

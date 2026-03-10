<?php

namespace App\Http\Middleware;

use App\Services\Permissions\PermissionManager;
use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function __construct(
        private PermissionManager $permissionManager
    ) {}

    public function handle(Request $request, Closure $next, string $permission)
    {
        $user = $request->user();
        
        if (!$user) {
            abort(401, 'Unauthenticated');
        }
        
        if (!$this->permissionManager->hasPermission($user, $permission)) {
            abort(403, 'Forbidden: Missing permission ' . $permission);
        }
        
        return $next($request);
    }
}

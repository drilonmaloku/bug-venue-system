<?php

namespace App\Providers;

use App\Services\Permissions\PermissionManager;
use Illuminate\Support\ServiceProvider;

class PermissionServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(PermissionManager::class, function () {
            return new PermissionManager();
        });
    }

    public function boot(): void
    {
        // Register blade directives
        \Blade::directive('canany', function ($expression) {
            return "<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any({$expression})): ?>";
        });

        \Blade::directive('endcanany', function () {
            return '<?php endif; ?>';
        });
        
        // Permission check directive
        \Blade::directive('permission', function ($expression) {
            $permission = trim($expression, '\'"');
            return "<?php if (auth()->check() && app(\App\Services\Permissions\PermissionManager::class)->hasPermission(auth()->user(), '{$permission}')): ?>";
        });

        \Blade::directive('endpermission', function () {
            return '<?php endif; ?>';
        });
    }
}

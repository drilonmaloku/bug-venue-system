<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')->group(base_path('routes/web.php'));
            Route::middleware('web')->group(base_path('app/Modules/Clients/Routes/clients-web.php'));
            Route::middleware('web')->group(base_path('app/Modules/Expenses/Routes/expenses-web.php'));
            Route::middleware('web')->group(base_path('app/Modules/Location/Routes/location-web.php'));
            Route::middleware('web')->group(base_path('app/Modules/Logs/Routes/logs-web.php'));
            Route::middleware('web')->group(base_path('app/Modules/Menus/Routes/menus-web.php'));
            Route::middleware('web')->group(base_path('app/Modules/Payments/Routes/payments-web.php'));
            Route::middleware('web')->group(base_path('app/Modules/Reports/Routes/reports-web.php'));
            Route::middleware('web')->group(base_path('app/Modules/Reservations/Routes/reservations-web.php'));
            Route::middleware('web')->group(base_path('app/Modules/Users/Routes/users-web.php'));
            Route::middleware('web')->group(base_path('app/Modules/Venues/Routes/venues-web.php'));
            Route::middleware('web')->group(base_path('app/Modules/Common/Routes/common-web.php'));
            Route::middleware('web')->group(base_path('app/Modules/SupportTickets/Routes/support-web.php'));


        });

        
    }
}

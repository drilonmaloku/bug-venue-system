<?php

namespace App\Providers;

use App\Http\Middleware\LogFailedRequests;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Http\Kernel;

class MiddlewareServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $kernel = $this->app->make(Kernel::class);
        
        // Register global middleware
        $kernel->pushMiddleware(LogFailedRequests::class);
    }
} 
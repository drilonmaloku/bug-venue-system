<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckLocationAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip check for system admins
        if (auth()->user()->hasRole('system-admin')) {
            return $next($request);
        }

        // Get the current user's location
        $userLocationId = auth()->user()->getCurrentLocationId();
        
        if (!$userLocationId) {
            return redirect()->back()->with('error', 'You do not have access to any location');
        }

        // Get the requested location ID from the route parameter
        $requestedLocation = $request->route('location');
        $requestedLocationId = $requestedLocation ? $requestedLocation->id : null;
        
        if ($requestedLocationId && $requestedLocationId != $userLocationId) {
            return redirect()->back()->with('error', 'You do not have access to this location');
        }

        return $next($request);
    }
} 
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LanguageSwitcher
{
    public function handle($request, Closure $next)
    {
        // Check if a locale is set in the session
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        }
        // If no session locale is set, use the authenticated user's language property if available
        else if (Auth::check() && Auth::user()->language) {
            App::setLocale(Auth::user()->language);
        }

        return $next($request);
    }
}
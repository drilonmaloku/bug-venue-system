<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleApiExceptions
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Add API headers to all responses
        $response->headers->set('X-API-Version', 'v1');
        $response->headers->set('X-Request-ID', uniqid('req_'));
        
        return $response;
    }
}

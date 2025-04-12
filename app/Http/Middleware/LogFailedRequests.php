<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogFailedRequests
{
    public function handle(Request $request, Closure $next)
    {

        $response = $next($request);

        if ($response->getStatusCode() >= 400) {
            Log::warning('Failed Request', [
                'url' => $request->fullUrl(),
                'status' => $response->getStatusCode(),
                'ip' => $request->ip(),
                'response' => $response->getContent(),
            ]);
        }

        return $response;
    }
}
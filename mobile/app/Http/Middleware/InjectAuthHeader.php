<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InjectAuthHeader
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If Authorization header is missing
        // but X-Auth-Token exists
        if (
            !$request->header('Authorization') &&
            $request->header('X-Auth-Token')
        ) {
            $token = trim($request->header('X-Auth-Token'));

            // Inject Bearer token for Sanctum
            $request->headers->set(
                'Authorization',
                'Bearer ' . $token
            );
        }

        return $next($request);
    }
}
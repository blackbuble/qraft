<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is super admin
        if (!auth()->check() || !auth()->user()->is_super_admin) {
            abort(403, 'Access denied. Super admin privileges required.');
        }

        return $next($request);
    }
}

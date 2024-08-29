<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserBasedAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role)
    {
        // Check if the selected role in the session matches the required role for the route
        if (session('selected_role') !== $role) {
            // Redirect to a 403 Forbidden page or an unauthorized access page
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}

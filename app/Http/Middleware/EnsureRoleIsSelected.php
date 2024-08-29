<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureRoleIsSelected
{
    public function handle($request, Closure $next)
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            $user = Auth::user();
            
            // Use the helper function to get active roles
            $activeRoles = getActiveRoles($user);

            // Check if a role has been selected in the session
            if (!$request->session()->has('selected_role')) {
                // If no role is selected and the user has multiple roles, redirect to role selection
                if (count($activeRoles) > 1) {
                    return redirect()->route('type.select');
                }

                 // If the user has only one role, auto-select it
                if (count($activeRoles) === 1) {
                    $selectedRole = array_key_first($activeRoles);
                    $request->session()->put('selected_role', $selectedRole);

                    // Redirect to the appropriate dashboard
                    return redirect()->route("{$selectedRole}.dashboard");
                }
            }

            // Proceed with the request if a role is selected
            return $next($request);
        }

        // If the user is not authenticated, redirect to the login page
        return redirect()->route('login');
    }
}

<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // HANDLE FAMILY GUARD AUTHENTICATION
        if (!$request->expectsJson()) {
            // Check if route expects family authentication
            if ($request->route() && str_contains($request->route()->getName(), 'families.') || 
                str_contains($request->route()->getName(), 'members.')) {
                return route('auth.login');
            }
            
            return route('auth.login');
        }
        
        return null;
    }
}

<?php
// ================================================================
// MIDDLEWARE UNTUK FAMILY AUTHENTICATION
// ================================================================

// app/Http/Middleware/FamilyAuth.php - COMPLETE VERSION

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FamilyAuth
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('family')->check()) {
            // Store intended URL for redirect after login
            session(['url.intended' => $request->fullUrl()]);
            
            return redirect()
                ->route('auth.login')
                ->with('error', 'Silakan login terlebih dahulu untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
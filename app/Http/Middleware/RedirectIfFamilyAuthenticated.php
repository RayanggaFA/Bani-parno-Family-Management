<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfFamilyAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if (Auth::guard('family')->check()) {
            // Jika sudah login dengan guard family → redirect ke halaman utama keluarga
            return redirect()->route('families.index');
        }

        return $next($request);
    }
}

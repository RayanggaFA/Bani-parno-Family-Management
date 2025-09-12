<?php
namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class GuestFamily
{
    public function handle($request, $next)
    {
        if (Auth::guard('family')->check()) {
            return redirect()->route('families.show', Auth::guard('family')->user());
        }

        return $next($request);
    }
}
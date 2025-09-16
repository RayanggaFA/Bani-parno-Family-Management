<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuestFamily
{
    /**
     * Handle an incoming request.
     * Redirect family yang sudah login ke dashboard mereka
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('family')->check()) {
            $family = Auth::guard('family')->user();
            return redirect()
                ->route('families.show', $family)
                ->with('info', 'Anda sudah login sebagai admin keluarga.');
        }

        return $next($request);
    }
}
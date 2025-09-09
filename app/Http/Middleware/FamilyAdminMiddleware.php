<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FamilyAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('family')->check()) {
            return redirect()->route('auth.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $family = Auth::guard('family')->user();
        
        // Check family ownership
        if ($request->route('family')) {
            $targetFamily = $request->route('family');
            if ($targetFamily->id !== $family->id) {
                abort(403, 'Anda hanya dapat mengelola keluarga Anda sendiri.');
            }
        }

        // Check member ownership
        if ($request->route('member')) {
            $targetMember = $request->route('member');
            if ($targetMember->family_id !== $family->id) {
                abort(403, 'Anda hanya dapat mengelola anggota keluarga Anda sendiri.');
            }
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPoliAccess
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (!$user) return redirect()->route('login');
        
        if ($user->role === 'admin' || $user->role === 'staff') {
            return $next($request);
        }
        
        if ($user->role === 'doctor' && $user->doctor) {
            $allowedPolis = $user->doctor->polyclinics()->pluck('polyclinics.id')->toArray();
            $poliId = $request->route('polyclinic');
            if ($poliId && !in_array($poliId, $allowedPolis)) {
                abort(403, 'Anda tidak memiliki akses ke poli ini.');
            }
        }
        
        return $next($request);
    }
}

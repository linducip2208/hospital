<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        if (! $request->user()) return redirect()->route('login');
        foreach ($permissions as $permission) {
            if ($request->user()->hasPermission($permission)) return $next($request);
        }
        abort(403, 'Anda tidak memiliki permission untuk aksi ini.');
    }
}

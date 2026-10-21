<?php

namespace App\Http\Middleware;

use App\Models\Permission;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Otorisasi resource web berbasis config/resource_permissions.php.
 * Fail-open: hanya menegakkan bila nama permission ADA di tabel permissions
 * (agar instalasi lama tidak terkunci sebelum seeder dijalankan).
 * Route yang sudah memakai middleware permission:/role:/can: dilewati.
 */
class RequireResourcePermission
{
    public function handle(Request $request, Closure $next): Response
    {
        $route = $request->route();
        $name = $route?->getName();
        if (! is_string($name)) {
            return $next($request);
        }

        foreach ((array) $route->gatherMiddleware() as $mw) {
            $mwName = is_string($mw) ? explode(':', $mw)[0] : null;
            if (in_array($mwName, ['permission', 'role', 'can'], true)) {
                return $next($request);
            }
        }

        $parts = explode('.', $name);
        $action = end($parts);
        $slug = implode('.', array_slice($parts, 0, -1));
        if ($slug === '' || ! in_array($action, ['index', 'show', 'create', 'store', 'edit', 'update', 'destroy'], true)) {
            return $next($request);
        }

        $map = config('resource_permissions.'.$slug);
        if (! is_array($map)) {
            return $next($request);
        }

        $kind = in_array($action, ['index', 'show'], true) ? 'view' : 'manage';
        $perm = $map[$kind] ?? null;
        if (! is_string($perm) || $perm === '') {
            return $next($request);
        }

        // Fail-open bila permission belum di-seed.
        if (! Permission::where('name', $perm)->exists()) {
            return $next($request);
        }

        $user = $request->user();
        if (! $user || ! $user->hasPermission($perm)) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['message' => 'Akses ditolak untuk peran Anda.'], 403);
            }
            abort(403, 'Akses ditolak untuk peran Anda.');
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RolConsulta
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
public function handle($request, Closure $next, ...$roles)
{
    if (!auth()->check()) {
        return redirect('/login');
    }

    $userRole = auth()->user()->rol;

    foreach ($roles as $role) {
        if ($userRole == $role) {
            return $next($request);
        }
    }

    abort(403, 'No autorizado');
}

}

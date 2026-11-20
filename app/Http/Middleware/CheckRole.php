<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! $request->user() || ! in_array($request->user()->rol, $roles)) {
            if ($request->user()) {
                return redirect('/home')->with('error', 'No tienes permiso para acceder a esta sección.');
            }
            return redirect()->route('login');
        }

        return $next($request);
    }
}
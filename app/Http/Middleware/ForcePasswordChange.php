<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordChange
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->must_change_password) {
            // Se o usuário estiver tentando alterar a senha ou fazer logout, permitir
            if ($request->routeIs('password.force.change') || $request->routeIs('password.force.update') || $request->routeIs('logout')) {
                return $next($request);
            }

            return redirect()->route('password.force.change');
        }

        return $next($request);
    }
}

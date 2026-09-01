<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckTenantAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::guard('web')->user();

        // Se não houver usuário ou ele não tiver um tenant associado (Super Admin no guard errado, etc)
        if (!$user || !$user->tenant) {
            return $next($request);
        }

        $tenant = $user->tenant;

        if ($tenant->status !== 'active') {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->with('error', 'Sua conta de lojista está desativada. Entre em contato com o suporte.');
        }

        if ($tenant->isExpired()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->with('error', 'Seu plano expirou. Renove sua assinatura para continuar acessando.');
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|array  $roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $roles)
    {
        if (!Auth::check()) {
            return redirect()->route('filament.auth.login'); // Redireciona para login do Filament se não autenticado
        }

        $user = Auth::user();

        if (!$user->hasAnyRole(explode('|', $roles))) {
            return redirect()->route('filament.pages.dashboard')->with('error', 'Acesso negado: você não tem permissão para acessar esta página.');
        }

        return $next($request);
    }
}

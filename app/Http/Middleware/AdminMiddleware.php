<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    // Verifica se o utilizador autenticado é administrador
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->eAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'Acesso restrito a administradores.');
        }

        return $next($request);
    }
}

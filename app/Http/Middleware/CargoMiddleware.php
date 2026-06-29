<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CargoMiddleware
{
    public function handle(Request $request, Closure $next, string ...$cargos)
    {
        $utilizador = Auth::user();

        if (!$utilizador) {
            return redirect()->route('login');
        }

        if ($utilizador->eAdmin()) {
            return $next($request);
        }

        foreach ($cargos as $cargo) {
            if ($utilizador->temCargo($cargo)) {
                return $next($request);
            }
        }

        return redirect()->route('dashboard')
            ->with('error', 'Não tens permissão para aceder a esta área.');
    }
}

<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AutenticacaoRequest;
use App\Services\AuditoriaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessaoController extends Controller
{
    public function mostrar()
    {
        return view('autenticacao.login');
    }

    public function entrar(AutenticacaoRequest $request)
    {
        $request->autenticar();
        $request->session()->regenerate();

        AuditoriaService::registar('login', 'utilizador', Auth::id(), [
            'email' => Auth::user()->email,
        ]);

        return redirect()->intended(route('painel'));
    }

    public function sair(Request $request)
    {
        if (Auth::check()) {
            AuditoriaService::registar('logout', 'utilizador', Auth::id());
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('inicio');
    }
}

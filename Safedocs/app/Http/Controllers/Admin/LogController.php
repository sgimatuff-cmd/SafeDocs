<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAuditoria;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function listar(Request $request)
    {
        $acaoFiltro = $request->input('acao');
        $pesquisa   = $request->input('pesquisa');

        $consulta = LogAuditoria::with('utilizador')->latest();

        if ($acaoFiltro) {
            $consulta->where('acao', $acaoFiltro);
        }

        if ($pesquisa) {
            $consulta->where(function ($q) use ($pesquisa) {
                $q->whereHas('utilizador', fn ($u) => $u->where('nome', 'LIKE', "%{$pesquisa}%"))
                  ->orWhere('acao', 'LIKE', "%{$pesquisa}%");
            });
        }

        $logs  = $consulta->paginate(25);
        $acoes = LogAuditoria::select('acao')->distinct()->orderBy('acao')->pluck('acao');

        return view('administracao.logs', compact('logs', 'acoes', 'acaoFiltro', 'pesquisa'));
    }
}

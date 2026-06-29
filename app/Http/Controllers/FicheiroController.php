<?php
namespace App\Http\Controllers;

use App\Models\Ficheiro;
use App\Models\Grupo;
use App\Models\LogAcesso;
use App\Services\AuditoriaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FicheiroController extends Controller
{
    public function index(Request $request)
    {
        $utilizador  = Auth::user();
        $pesquisa    = $request->input('pesquisa');
        $grupoFiltro = $request->input('grupo_id');

        $consulta = Ficheiro::with(['grupo', 'autor']);

        if (!$utilizador->eAdmin()) {
            $idsGrupos = $utilizador->grupos->pluck('id');
            $consulta->whereIn('grupo_id', $idsGrupos);
        }

        if ($pesquisa) {
            $consulta->where('nome_exibicao', 'LIKE', "%{$pesquisa}%");
        }

        if ($grupoFiltro) {
            $consulta->where('grupo_id', $grupoFiltro);
        }

        $ficheiros = $consulta->latest()->paginate(15);
        $grupos    = $utilizador->eAdmin() ? Grupo::all() : $utilizador->grupos;

        return view('files.index', compact('ficheiros', 'pesquisa', 'grupos', 'grupoFiltro'));
    }

    public function create()
    {
        $utilizador = Auth::user();
        $grupos     = $utilizador->eAdmin() ? Grupo::all() : $utilizador->grupos;

        return view('files.create', compact('grupos'));
    }

    public function store(Request $request)
    {
        $utilizador = Auth::user();

        $request->validate([
            'ficheiro'      => 'required|file|max:102400',
            'nome_exibicao' => 'required|string|max:255',
            'grupo_id'      => 'required|exists:grupos,id',
            'expira_em'     => 'nullable|date|after:now',
        ], [
            'ficheiro.required'      => 'Seleciona um ficheiro.',
            'ficheiro.max'           => 'O ficheiro não pode ter mais de 100MB.',
            'nome_exibicao.required' => 'O nome de exibição é obrigatório.',
            'grupo_id.required'      => 'Seleciona um grupo.',
            'grupo_id.exists'        => 'O grupo selecionado não existe.',
            'expira_em.after'        => 'A data de expiração tem de ser no futuro.',
        ]);

        if (!$utilizador->eAdmin()) {
            $idsGrupos = $utilizador->grupos->pluck('id');
            if (!$idsGrupos->contains($request->grupo_id)) {
                return back()->with('error', 'Não tens permissão para carregar ficheiros para esse grupo.');
            }
        }

        $f              = $request->file('ficheiro');
        $nomeArmazenado = Str::uuid() . '.' . $f->getClientOriginalExtension();

        Storage::disk('public')->putFileAs('ficheiros', $f, $nomeArmazenado);

        $ficheiro = Ficheiro::create([
            'nome_exibicao'   => $request->nome_exibicao,
            'nome_original'   => $f->getClientOriginalName(),
            'nome_armazenado' => $nomeArmazenado,
            'tipo_mime'       => $f->getMimeType(),
            'tamanho'         => $f->getSize(),
            'grupo_id'        => $request->grupo_id,
            'carregado_por'   => Auth::id(),
            'expira_em'       => $request->expira_em ?: null,
        ]);

        AuditoriaService::registar('ficheiro.upload', 'ficheiro', $ficheiro->id, [
            'nome'     => $ficheiro->nome_exibicao,
            'grupo_id' => $ficheiro->grupo_id,
        ]);

        return redirect()->route('ficheiros.index')
            ->with('success', 'Ficheiro carregado com sucesso!');
    }

    public function download(Ficheiro $ficheiro)
    {
        $utilizador = Auth::user();

        if (!$utilizador->eAdmin() && !$utilizador->grupos->contains($ficheiro->grupo_id)) {
            return back()->with('error', 'Não tens permissão para descarregar este ficheiro.');
        }

        if ($ficheiro->estaExpirado()) {
            return back()->with('error', 'Este ficheiro expirou e já não está disponível para download.');
        }

        $caminho = storage_path('app/public/ficheiros/' . $ficheiro->nome_armazenado);

        if (!file_exists($caminho)) {
            return back()->with('error', 'Ficheiro não encontrado no servidor.');
        }

        LogAcesso::create([
            'ficheiro_id'   => $ficheiro->id,
            'utilizador_id' => $utilizador->id,
            'acao'          => 'download',
            'ip'            => request()->ip(),
        ]);

        AuditoriaService::registar('ficheiro.download', 'ficheiro', $ficheiro->id, [
            'nome' => $ficheiro->nome_exibicao,
        ]);

        return response()->download($caminho, $ficheiro->nome_original);
    }

    public function destroy(Ficheiro $ficheiro)
    {
        Storage::disk('public')->delete('ficheiros/' . $ficheiro->nome_armazenado);

        AuditoriaService::registar('ficheiro.eliminar', 'ficheiro', $ficheiro->id, [
            'nome' => $ficheiro->nome_exibicao,
        ]);

        $ficheiro->delete();

        return redirect()->route('ficheiros.index')
            ->with('success', 'Ficheiro eliminado com sucesso!');
    }
}

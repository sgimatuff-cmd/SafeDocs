<?php
namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\Utilizador;
use App\Services\AuditoriaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MeuGrupoController extends Controller
{
    /**
     * Lista o(s) grupo(s) a que o operador pertence, com os respetivos membros.
     * Um administrador pode ver todos os grupos a partir daqui também.
     */
    public function mostrar()
    {
        $utilizador = Auth::user();

        $grupos = $utilizador->eAdmin()
            ? Grupo::with('utilizadores')->withCount('ficheiros')->get()
            : $utilizador->grupos()->with('utilizadores')->withCount('ficheiros')->get();

        return view('meu-grupo.mostrar', compact('grupos'));
    }

    public function adicionarMembro(Request $request, Grupo $grupo)
    {
        $this->autorizarGrupo($grupo);

        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Indica o email do utilizador a adicionar.',
            'email.email'    => 'Email inválido.',
        ]);

        $novoMembro = Utilizador::where('email', $request->email)->first();

        if (!$novoMembro) {
            return back()->with('error', 'Não existe nenhum utilizador registado com esse email.');
        }

        if ($grupo->utilizadores->contains($novoMembro->id)) {
            return back()->with('error', '"' . $novoMembro->nome . '" já pertence a este grupo.');
        }

        $grupo->utilizadores()->attach($novoMembro->id);

        AuditoriaService::registar('grupo.adicionar_utilizador', 'utilizador', $novoMembro->id, [
            'grupo' => $grupo->nome,
        ]);

        return back()->with('success', '"' . $novoMembro->nome . '" adicionado ao grupo "' . $grupo->nome . '".');
    }

    public function removerMembro(Grupo $grupo, Utilizador $utilizador)
    {
        $this->autorizarGrupo($grupo);

        if ($grupo->nome === 'Grupo Geral') {
            return back()->with('error', 'Não é possível remover utilizadores do Grupo Geral.');
        }

        if ($utilizador->id === Auth::id()) {
            return back()->with('error', 'Não podes remover-te a ti próprio do grupo.');
        }

        $grupo->utilizadores()->detach($utilizador->id);

        AuditoriaService::registar('grupo.remover_utilizador', 'utilizador', $utilizador->id, [
            'grupo' => $grupo->nome,
        ]);

        return back()->with('success', '"' . $utilizador->nome . '" removido do grupo "' . $grupo->nome . '".');
    }

    /**
     * Garante que o operador só gere o(s) grupo(s) a que pertence.
     * O administrador pode gerir qualquer grupo.
     */
    private function autorizarGrupo(Grupo $grupo): void
    {
        $utilizador = Auth::user();

        if ($utilizador->eAdmin()) {
            return;
        }

        if (!$utilizador->grupos->contains($grupo->id)) {
            throw new HttpException(403, 'Não tens permissão para gerir este grupo.');
        }
    }
}

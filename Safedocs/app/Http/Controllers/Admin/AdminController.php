<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cargo;
use App\Models\Grupo;
use App\Models\Utilizador;
use App\Services\AuditoriaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function utilizadores()
    {
        $utilizadores = Utilizador::withCount('ficheiros')
            ->with(['grupos', 'cargos'])
            ->latest()
            ->paginate(20);

        $cargos = Cargo::orderBy('nivel')->get();

        return view('administracao.utilizadores', compact('utilizadores', 'cargos'));
    }

    public function promover(Request $request, Utilizador $utilizador)
    {
        $request->validate([
            'password_confirmacao' => 'required|string',
        ], ['password_confirmacao.required' => 'Introduz a tua password para confirmar.']);

        if (!Hash::check($request->password_confirmacao, Auth::user()->palavra_passe)) {
            return back()->with('error', 'Password incorreta. Ação cancelada.');
        }

        if ($utilizador->e_administrador) {
            return back()->with('error', 'Este utilizador já é administrador.');
        }

        $utilizador->update(['e_administrador' => true]);

        $cargoAdmin = Cargo::where('slug', 'admin')->first();
        if ($cargoAdmin && !$utilizador->temCargo('admin')) {
            $utilizador->cargos()->attach($cargoAdmin->id);
        }

        AuditoriaService::registar('utilizador.promover', 'utilizador', $utilizador->id, [
            'nome' => $utilizador->nome,
        ]);

        return back()->with('success', '"' . $utilizador->nome . '" é agora administrador.');
    }

    public function promoverOperador(Utilizador $utilizador)
    {
        if ($utilizador->temCargo('operador')) {
            return back()->with('error', 'Este utilizador já é operador.');
        }

        $cargoOperador = Cargo::where('slug', 'operador')->first();

        if (!$cargoOperador) {
            return back()->with('error', 'O cargo de Operador não existe.');
        }

        $utilizador->cargos()->attach($cargoOperador->id);

        AuditoriaService::registar('utilizador.promover_operador', 'utilizador', $utilizador->id, [
            'nome' => $utilizador->nome,
        ]);

        return back()->with('success', '"' . $utilizador->nome . '" é agora operador.');
    }

    public function eliminar(Utilizador $utilizador)
    {
        if ($utilizador->id === Auth::id()) {
            return back()->with('error', 'Não podes remover a tua própria conta.');
        }

        $nome = $utilizador->nome;

        AuditoriaService::registar('utilizador.eliminar', 'utilizador', $utilizador->id, [
            'nome' => $nome,
        ]);

        $utilizador->delete();

        return redirect()->route('admin.utilizadores')
            ->with('success', 'Utilizador "' . $nome . '" removido com sucesso.');
    }

    public function adicionarAoGrupo(Request $request, Utilizador $utilizador)
    {
        $request->validate([
            'grupo_id' => 'required|exists:grupos,id',
        ], ['grupo_id.required' => 'Seleciona um grupo.']);

        if ($utilizador->grupos->contains($request->grupo_id)) {
            return back()->with('error', '"' . $utilizador->nome . '" já pertence a esse grupo.');
        }

        $utilizador->grupos()->attach($request->grupo_id);
        $grupo = Grupo::find($request->grupo_id);

        AuditoriaService::registar('grupo.adicionar_utilizador', 'utilizador', $utilizador->id, [
            'grupo' => $grupo->nome,
        ]);

        return back()->with('success', '"' . $utilizador->nome . '" adicionado ao grupo "' . $grupo->nome . '".');
    }

    public function removerDoGrupo(Request $request, Utilizador $utilizador, Grupo $grupo)
    {
        if ($grupo->nome === 'Grupo Geral') {
            return back()->with('error', 'Não é possível remover utilizadores do Grupo Geral.');
        }

        $utilizador->grupos()->detach($grupo->id);

        AuditoriaService::registar('grupo.remover_utilizador', 'utilizador', $utilizador->id, [
            'grupo' => $grupo->nome,
        ]);

        return back()->with('success', '"' . $utilizador->nome . '" removido do grupo "' . $grupo->nome . '".');
    }

    public function adicionarCargo(Request $request, Utilizador $utilizador)
    {
        $request->validate([
            'cargo_id' => 'required|exists:cargos,id',
        ], ['cargo_id.required' => 'Seleciona um cargo.']);

        $cargo = Cargo::find($request->cargo_id);

        if ($utilizador->cargos->contains($cargo->id)) {
            return back()->with('error', '"' . $utilizador->nome . '" já tem o cargo "' . $cargo->nome . '".');
        }

        $utilizador->cargos()->attach($cargo->id);

        if ($cargo->slug === 'admin') {
            $utilizador->update(['e_administrador' => true]);
        }

        AuditoriaService::registar('cargo.adicionar', 'utilizador', $utilizador->id, [
            'cargo' => $cargo->nome,
        ]);

        return back()->with('success', 'Cargo "' . $cargo->nome . '" atribuído a "' . $utilizador->nome . '".');
    }

    public function removerCargo(Utilizador $utilizador, Cargo $cargo)
    {
        if ($utilizador->id === Auth::id() && $cargo->slug === 'admin') {
            return back()->with('error', 'Não podes remover o teu próprio cargo de administrador.');
        }

        if ($cargo->slug === 'utilizador' && $utilizador->cargos->count() <= 1) {
            return back()->with('error', 'O utilizador tem de manter pelo menos o cargo de Utilizador.');
        }

        $utilizador->cargos()->detach($cargo->id);

        if ($cargo->slug === 'admin') {
            $utilizador->update(['e_administrador' => false]);
        }

        AuditoriaService::registar('cargo.remover', 'utilizador', $utilizador->id, [
            'cargo' => $cargo->nome,
        ]);

        return back()->with('success', 'Cargo "' . $cargo->nome . '" removido de "' . $utilizador->nome . '".');
    }
}

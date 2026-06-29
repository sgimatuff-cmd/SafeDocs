<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grupo;
use App\Services\AuditoriaService;
use Illuminate\Http\Request;

class GrupoController extends Controller
{
    public function index()
    {
        $grupos = Grupo::withCount('utilizadores')->withCount('ficheiros')->latest()->get();
        return view('admin.grupos', compact('grupos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:100|unique:grupos,nome',
        ], [
            'nome.required' => 'O nome do grupo é obrigatório.',
            'nome.unique'   => 'Já existe um grupo com esse nome.',
        ]);

        $grupo = Grupo::create(['nome' => $request->nome]);

        AuditoriaService::registar('grupo.criar', 'grupo', $grupo->id, [
            'nome' => $grupo->nome,
        ]);

        return back()->with('success', 'Grupo "' . $request->nome . '" criado com sucesso!');
    }

    public function destroy(Grupo $grupo)
    {
        if ($grupo->nome === 'Grupo Geral') {
            return back()->with('error', 'O Grupo Geral não pode ser eliminado.');
        }

        $nome = $grupo->nome;

        AuditoriaService::registar('grupo.eliminar', 'grupo', $grupo->id, [
            'nome' => $nome,
        ]);

        $grupo->delete();

        return back()->with('success', 'Grupo "' . $nome . '" eliminado.');
    }
}

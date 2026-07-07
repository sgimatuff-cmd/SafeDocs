<?php
namespace App\Http\Controllers;

use App\Models\Ficheiro;
use App\Models\Grupo;
use App\Models\Utilizador;
use Illuminate\Support\Facades\Auth;

class PainelController extends Controller
{
    public function mostrar()
    {
        $utilizador = Auth::user();

        if ($utilizador->eAdmin()) {
            $totalFicheiros    = Ficheiro::count();
            $totalUtilizadores = Utilizador::count();
            $totalGrupos       = Grupo::count();
            $ultimosFicheiros  = Ficheiro::with(['grupo', 'autor'])->latest()->take(5)->get();

            return view('painel', compact(
                'totalFicheiros', 'totalUtilizadores', 'totalGrupos', 'ultimosFicheiros'
            ));
        }

        $idsGrupos = $utilizador->grupos->pluck('id');
        $ultimosFicheiros = Ficheiro::with('grupo')
            ->whereIn('grupo_id', $idsGrupos)
            ->latest()
            ->take(5)
            ->get();
        $totalFicheiros = Ficheiro::whereIn('grupo_id', $idsGrupos)->count();

        return view('painel', compact('ultimosFicheiros', 'totalFicheiros'));
    }
}

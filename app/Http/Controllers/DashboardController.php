<?php
namespace App\Http\Controllers;

use App\Models\Ficheiro;
use App\Models\Grupo;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $utilizador = Auth::user();

        if ($utilizador->eAdmin()) {
            $totalFicheiros    = Ficheiro::count();
            $totalUtilizadores = User::count();
            $totalGrupos       = Grupo::count();
            $ultimosFicheiros  = Ficheiro::with(['grupo', 'autor'])->latest()->take(5)->get();

            return view('dashboard', compact(
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

        return view('dashboard', compact('ultimosFicheiros', 'totalFicheiros'));
    }
}

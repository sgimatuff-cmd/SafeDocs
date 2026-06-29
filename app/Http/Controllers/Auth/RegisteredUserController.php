<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Cargo;
use App\Models\Grupo;
use App\Models\User;
use App\Services\AuditoriaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:utilizadores'],
            'palavra_passe' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'nome.required'           => 'O nome é obrigatório.',
            'email.required'          => 'O email é obrigatório.',
            'email.unique'            => 'Este email já está registado.',
            'palavra_passe.required'  => 'A palavra-passe é obrigatória.',
            'palavra_passe.confirmed' => 'As palavras-passe não coincidem.',
        ]);

        $utilizador = User::create([
            'nome'            => $request->nome,
            'email'           => $request->email,
            'palavra_passe'   => Hash::make($request->palavra_passe),
            'e_administrador' => false,
        ]);

        $grupoGeral = Grupo::where('nome', 'Grupo Geral')->first();
        if ($grupoGeral) {
            $utilizador->grupos()->attach($grupoGeral->id);
        }

        $cargoUtilizador = Cargo::where('slug', 'utilizador')->first();
        if ($cargoUtilizador) {
            $utilizador->cargos()->attach($cargoUtilizador->id);
        }

        Auth::login($utilizador);

        AuditoriaService::registar('registo', 'utilizador', $utilizador->id, [
            'email' => $utilizador->email,
        ]);

        return redirect()->route('dashboard');
    }
}

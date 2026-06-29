<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Cargo;
use App\Models\Grupo;
use App\Models\User;
use App\Services\AuditoriaService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Não foi possível autenticar com a Google. Tenta novamente.');
        }

        $utilizador = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if ($utilizador) {
            if (!$utilizador->google_id) {
                $utilizador->update(['google_id' => $googleUser->getId()]);
            }
        } else {
            $utilizador = User::create([
                'nome'          => $googleUser->getName() ?? $googleUser->getNickname() ?? 'Utilizador Google',
                'email'         => $googleUser->getEmail(),
                'google_id'     => $googleUser->getId(),
                'palavra_passe' => Hash::make(Str::random(32)),
            ]);

            $grupoGeral = Grupo::where('nome', 'Grupo Geral')->first();
            if ($grupoGeral) {
                $utilizador->grupos()->attach($grupoGeral->id);
            }

            $cargoUtilizador = Cargo::where('slug', 'utilizador')->first();
            if ($cargoUtilizador) {
                $utilizador->cargos()->attach($cargoUtilizador->id);
            }

            AuditoriaService::registar('registo', 'utilizador', $utilizador->id, [
                'metodo' => 'google',
                'email'  => $utilizador->email,
            ]);
        }

        Auth::login($utilizador, true);

        AuditoriaService::registar('login.google', 'utilizador', $utilizador->id, [
            'email' => $utilizador->email,
        ]);

        return redirect()->route('dashboard');
    }
}

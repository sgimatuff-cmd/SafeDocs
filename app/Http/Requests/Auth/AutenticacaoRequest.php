<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AutenticacaoRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'email'         => ['required', 'string', 'email'],
            'palavra_passe' => ['required', 'string'], // Nome do campo no HTML
        ];
    }

    public function autenticar(): void
    {
        $this->garantirSemLimiteTentativas();

        // O Laravel espera 'password' como chave para verificar a hash.
        // Passamos 'palavra_passe' (o que vem do formulário) para a chave 'password'.
        if (!Auth::attempt([
            'email'    => $this->email,
            'password' => $this->palavra_passe,
        ], $this->boolean('lembrar'))) {

            RateLimiter::hit($this->chaveLimitador());

            throw ValidationException::withMessages([
                'email' => 'Email ou palavra-passe incorretos.',
            ]);
        }

        RateLimiter::clear($this->chaveLimitador());
    }

    public function garantirSemLimiteTentativas(): void
    {
        if (!RateLimiter::tooManyAttempts($this->chaveLimitador(), 5)) return;

        event(new Lockout($this));
        $segundos = RateLimiter::availableIn($this->chaveLimitador());
        throw ValidationException::withMessages([
            'email' => 'Demasiadas tentativas. Aguarde ' . ceil($segundos / 60) . ' minuto(s).',
        ]);
    }

    public function chaveLimitador(): string
    {
        return Str::transliterate(Str::lower($this->string('email')) . '|' . $this->ip());
    }
}

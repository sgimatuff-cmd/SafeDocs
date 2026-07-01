<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'email'         => ['required', 'string', 'email'],
            'palavra_passe' => ['required', 'string'], // Nome do campo no seu HTML
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // O Laravel espera 'password' como chave para verificar a hash.
        // Passamos 'palavra_passe' (o que vem do form) para a chave 'password'.
        if (!Auth::attempt([
            'email'    => $this->email,
            'password' => $this->palavra_passe, 
        ], $this->boolean('lembrar'))) {
            
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => 'Email ou palavra-passe incorretos.',
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    public function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) return; // Sugiro baixar para 5 tentativas

        event(new Lockout($this));
        $seconds = RateLimiter::availableIn($this->throttleKey());
        throw ValidationException::withMessages([
            'email' => 'Demasiadas tentativas. Aguarde ' . ceil($seconds / 60) . ' minuto(s).',
        ]);
    }

    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')) . '|' . $this->ip());
    }
}
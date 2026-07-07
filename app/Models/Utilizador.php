<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Utilizador extends Authenticatable
{
    use HasFactory, Notifiable;

    // Define a tabela correta no Supabase
    protected $table = 'utilizadores';

    // Campos preenchíveis
    protected $fillable = ['nome', 'email', 'palavra_passe', 'e_administrador'];

    // Esconde a password e o token de sessão por segurança
    protected $hidden = ['palavra_passe', 'remember_token'];

    // Define os tipos de dados para as colunas
    protected function casts(): array
    {
        return [
            'e_administrador' => 'boolean',
            'email_verified_at' => 'datetime',
            // Importante: 'hashed' informa o Laravel que ao guardar no futuro, deve encriptar
            'palavra_passe' => 'hashed', 
        ];
    }

    // --- Relações ---

    public function grupos()
    {
        return $this->belongsToMany(Grupo::class, 'grupo_utilizador', 'utilizador_id', 'grupo_id');
    }

    public function ficheiros()
    {
        return $this->hasMany(Ficheiro::class, 'carregado_por');
    }

    public function cargos()
    {
        return $this->belongsToMany(Cargo::class, 'cargo_utilizador', 'utilizador_id', 'cargo_id');
    }

    // --- Métodos de Autorização ---

    public function temCargo(string $slug): bool
    {
        return $this->cargos->contains('slug', $slug);
    }

    public function temAlgumCargo(array $slugs): bool
    {
        return $this->cargos->whereIn('slug', $slugs)->isNotEmpty();
    }

    public function eAdmin(): bool
    {
        return (bool) $this->e_administrador || $this->temCargo('admin');
    }

    public function podeCarregarFicheiros(): bool
    {
        // Qualquer utilizador autenticado (utilizador, operador ou admin) pode
        // carregar ficheiros para os grupos a que pertence.
        return $this->eAdmin() || $this->temAlgumCargo(['utilizador', 'operador']);
    }

    public function podeEliminarFicheiros(): bool
    {
        // Utilizadores comuns só veem/transferem; operadores e admins podem eliminar.
        return $this->eAdmin() || $this->temCargo('operador');
    }

    public function podeGerirUtilizadores(): bool
    {
        return $this->eAdmin();
    }

    public function podeGerirGrupos(): bool
    {
        return $this->eAdmin();
    }

    public function podeVerLogs(): bool
    {
        return $this->eAdmin();
    }

    // Um operador é "o admin" do(s) seu(s) grupo(s): gere quem pertence a esse grupo.
    public function podeGerirProprioGrupo(): bool
    {
        return $this->eAdmin() || $this->temCargo('operador');
    }

    // --- Método para o Auth do Laravel usar a coluna personalizada ---

    /**
     * Get the password for the user.
     * O Laravel chama este método internamente durante o Auth::attempt()
     */
    public function getAuthPassword(): string
    {
        return $this->palavra_passe;
    }
}
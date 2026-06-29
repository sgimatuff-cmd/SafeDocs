<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'utilizadores';

    protected $fillable = ['nome', 'email', 'palavra_passe', 'e_administrador', 'google_id'];

    protected $hidden = ['palavra_passe', 'remember_token'];

    protected function casts(): array
    {
        return [
            'palavra_passe'   => 'hashed',
            'e_administrador' => 'boolean',
        ];
    }

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
        return $this->eAdmin() || $this->temAlgumCargo(['operador', 'moderador']);
    }

    public function podeEliminarFicheiros(): bool
    {
        return $this->eAdmin() || $this->temCargo('moderador');
    }

    public function podeGerirUtilizadores(): bool
    {
        return $this->eAdmin() || $this->temCargo('moderador');
    }

    public function podeGerirGrupos(): bool
    {
        return $this->eAdmin();
    }

    public function podeVerLogs(): bool
    {
        return $this->eAdmin() || $this->temCargo('moderador');
    }

    public function getAuthPassword()
    {
        return $this->palavra_passe;
    }
}

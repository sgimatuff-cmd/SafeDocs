<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    protected $table = 'cargos';

    protected $fillable = ['nome', 'slug', 'nivel', 'descricao'];

    public function utilizadores()
    {
        return $this->belongsToMany(User::class, 'cargo_utilizador', 'cargo_id', 'utilizador_id');
    }

    public function corBadge(): string
    {
        return match ($this->slug) {
            'admin'      => 'bg-primary',
            'moderador'  => 'bg-warning text-dark',
            'operador'   => 'bg-info text-dark',
            default      => 'bg-secondary',
        };
    }
}

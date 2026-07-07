<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    protected $table = 'cargos';

    protected $fillable = ['nome', 'slug', 'nivel', 'descricao'];

    public function utilizadores()
    {
        return $this->belongsToMany(Utilizador::class, 'cargo_utilizador', 'cargo_id', 'utilizador_id');
    }

    public function corBadge(): string
    {
        return match ($this->slug) {
            'admin'      => 'bg-primary',
            'operador'   => 'bg-info text-dark',
            default      => 'bg-secondary',
        };
    }
}

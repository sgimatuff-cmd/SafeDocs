<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    protected $table = 'grupos';
    protected $fillable = ['nome'];

    // Utilizadores que pertencem a este grupo
    public function utilizadores()
    {
        return $this->belongsToMany(Utilizador::class, 'grupo_utilizador', 'grupo_id', 'utilizador_id');
    }

    // Ficheiros associados a este grupo
    public function ficheiros()
    {
        return $this->hasMany(Ficheiro::class, 'grupo_id');
    }
}

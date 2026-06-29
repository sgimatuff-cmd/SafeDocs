<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAcesso extends Model
{
    protected $table = 'logs_acesso';

    protected $fillable = [
        'ficheiro_id',
        'utilizador_id',
        'acao',
        'ip',
    ];

    // Ficheiro a que pertence este log
    public function ficheiro()
    {
        return $this->belongsTo(Ficheiro::class, 'ficheiro_id');
    }

    // Utilizador que fez o acesso
    public function utilizador()
    {
        return $this->belongsTo(User::class, 'utilizador_id');
    }
}

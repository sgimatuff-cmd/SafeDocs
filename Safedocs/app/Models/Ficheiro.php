<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ficheiro extends Model
{
    protected $table = 'ficheiros';

    protected $fillable = [
        'nome_exibicao',
        'nome_original',
        'nome_armazenado',
        'tipo_mime',
        'tamanho',
        'grupo_id',
        'carregado_por',
        'expira_em',
    ];

    protected $casts = [
        'expira_em' => 'datetime',
    ];

    // Grupo a que pertence este ficheiro
    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'grupo_id');
    }

    // Utilizador que carregou o ficheiro
    public function autor()
    {
        return $this->belongsTo(Utilizador::class, 'carregado_por');
    }

    // Logs de acesso a este ficheiro
    public function logs()
    {
        return $this->hasMany(LogAcesso::class, 'ficheiro_id');
    }

    // Verificar se o ficheiro está expirado
    public function estaExpirado(): bool
    {
        return $this->expira_em !== null && $this->expira_em->isPast();
    }

    // Tamanho legível: "2.5 MB" ou "340 KB"
    public function tamanhoFormatado(): string
    {
        if ($this->tamanho >= 1048576) {
            return number_format($this->tamanho / 1048576, 2) . ' MB';
        }
        return number_format($this->tamanho / 1024, 2) . ' KB';
    }

    // Ícone Bootstrap consoante o tipo de ficheiro
    public function icone(): string
    {
        return match(true) {
            str_contains($this->tipo_mime ?? '', 'pdf')   => 'bi-file-earmark-pdf text-danger',
            str_contains($this->tipo_mime ?? '', 'word')  => 'bi-file-earmark-word text-primary',
            str_contains($this->tipo_mime ?? '', 'excel') ||
            str_contains($this->tipo_mime ?? '', 'sheet') => 'bi-file-earmark-excel text-success',
            str_contains($this->tipo_mime ?? '', 'image') => 'bi-file-earmark-image text-info',
            str_contains($this->tipo_mime ?? '', 'zip') ||
            str_contains($this->tipo_mime ?? '', 'rar')   => 'bi-file-earmark-zip text-warning',
            default => 'bi-file-earmark text-secondary',
        };
    }
}

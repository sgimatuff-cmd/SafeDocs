<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAuditoria extends Model
{
    protected $table = 'logs_auditoria';

    protected $fillable = [
        'utilizador_id',
        'acao',
        'entidade',
        'entidade_id',
        'detalhes',
        'ip',
    ];

    protected $casts = [
        'detalhes' => 'array',
    ];

    public function utilizador()
    {
        return $this->belongsTo(User::class, 'utilizador_id');
    }

    public function acaoLegivel(): string
    {
        return match ($this->acao) {
            'login'               => 'Início de sessão',
            'logout'              => 'Fim de sessão',
            'registo'             => 'Registo de conta',
            'login.google'        => 'Início de sessão (Google)',
            'ficheiro.upload'     => 'Upload de ficheiro',
            'ficheiro.download'   => 'Download de ficheiro',
            'ficheiro.eliminar'   => 'Eliminação de ficheiro',
            'utilizador.promover' => 'Promoção a admin',
            'utilizador.eliminar' => 'Eliminação de utilizador',
            'grupo.criar'         => 'Criação de grupo',
            'grupo.eliminar'      => 'Eliminação de grupo',
            'cargo.adicionar'     => 'Atribuição de cargo',
            'cargo.remover'       => 'Remoção de cargo',
            default               => ucfirst(str_replace('.', ' — ', $this->acao)),
        };
    }

    public function iconeAcao(): string
    {
        return match (true) {
            str_contains($this->acao, 'login')    => 'bi-box-arrow-in-right text-success',
            str_contains($this->acao, 'logout')   => 'bi-box-arrow-right text-secondary',
            str_contains($this->acao, 'upload')   => 'bi-upload text-primary',
            str_contains($this->acao, 'download') => 'bi-download text-success',
            str_contains($this->acao, 'eliminar') => 'bi-trash text-danger',
            str_contains($this->acao, 'cargo')    => 'bi-shield text-info',
            default                               => 'bi-journal-text text-muted',
        };
    }
}

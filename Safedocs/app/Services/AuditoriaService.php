<?php
namespace App\Services;

use App\Models\LogAuditoria;
use Illuminate\Support\Facades\Auth;

class AuditoriaService
{
    public static function registar(
        string $acao,
        ?string $entidade = null,
        ?int $entidadeId = null,
        array $detalhes = []
    ): void {
        $utilizador = Auth::user();
        if (!$utilizador) {
            return;
        }

        LogAuditoria::create([
            'utilizador_id' => $utilizador->id,
            'acao'          => $acao,
            'entidade'      => $entidade,
            'entidade_id'   => $entidadeId,
            'detalhes'      => $detalhes ?: null,
            'ip'            => request()->ip(),
        ]);
    }
}

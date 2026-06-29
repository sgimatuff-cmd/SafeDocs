@extends('layouts.app')
@section('titulo', 'Logs de Auditoria — SafeDocs')
@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="fw-bold mb-0">Logs de Auditoria</h5>
        <p class="text-muted small mb-0">Registo automático de todas as ações importantes no sistema.</p>
    </div>
</div>

<form method="GET" action="{{ route('admin.logs') }}" class="mb-4">
    <div class="d-flex flex-wrap gap-2 align-items-center">
        <input type="text" name="pesquisa" class="form-control form-control-sm" style="max-width:220px"
               placeholder="Pesquisar utilizador..." value="{{ $pesquisa ?? '' }}">
        <select name="acao" class="form-select form-select-sm" style="max-width:260px">
            <option value="">Todas as ações</option>
            @foreach($acoes as $a)
                <option value="{{ $a }}" {{ ($acaoFiltro ?? '') === $a ? 'selected' : '' }}>
                    {{ $a }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-funnel me-1"></i>Filtrar
        </button>
        @if($pesquisa || $acaoFiltro)
            <a href="{{ route('admin.logs') }}" class="btn btn-outline-danger btn-sm">
                <i class="bi bi-x me-1"></i>Limpar
            </a>
        @endif
    </div>
</form>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Utilizador</th>
                        <th>Ação</th>
                        <th>Entidade</th>
                        <th>Detalhes</th>
                        <th>IP</th>
                        <th>Data e hora</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td class="small fw-semibold">
                            <i class="bi bi-person-circle me-1 text-muted"></i>
                            {{ $log->utilizador->nome ?? '—' }}
                        </td>
                        <td>
                            <span class="badge bg-light text-dark small">
                                <i class="bi {{ $log->iconeAcao() }} me-1"></i>
                                {{ $log->acaoLegivel() }}
                            </span>
                        </td>
                        <td class="small text-muted">
                            @if($log->entidade)
                                {{ $log->entidade }} #{{ $log->entidade_id }}
                            @else
                                —
                            @endif
                        </td>
                        <td class="small text-muted">
                            @if($log->detalhes)
                                @foreach($log->detalhes as $chave => $valor)
                                    <span class="d-block"><strong>{{ $chave }}:</strong> {{ is_array($valor) ? json_encode($valor) : $valor }}</span>
                                @endforeach
                            @else
                                —
                            @endif
                        </td>
                        <td class="small text-muted font-monospace">{{ $log->ip ?? '—' }}</td>
                        <td class="small text-muted">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4 small">
                            <i class="bi bi-journal-x fs-4 d-block mb-2"></i>
                            Nenhum registo encontrado.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($logs->hasPages())
<div class="mt-3">{{ $logs->appends(request()->query())->links() }}</div>
@endif

@endsection

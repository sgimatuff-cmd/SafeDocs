@extends('modelos.aplicacao')
@section('titulo', 'Painel - SafeDocs')
@section('content')

@if(auth()->user()->eAdmin())

<div class="mb-4">
    <h5 class="fw-bold mb-0">Painel de Administração</h5>
    <p class="text-muted small">Visão geral do sistema SafeDocs.</p>
</div>

{{-- Contadores --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4">
        <div class="stat-card text-center">
            <div class="fs-1 fw-bold">{{ $totalFicheiros }}</div>
            <div class="text-muted small"><i class="bi bi-file-earmark me-1"></i>Ficheiros</div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="stat-card text-center">
            <div class="fs-1 fw-bold">{{ $totalUtilizadores }}</div>
            <div class="text-muted small"><i class="bi bi-people me-1"></i>Utilizadores</div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="stat-card text-center">
            <div class="fs-1 fw-bold">{{ $totalGrupos }}</div>
            <div class="text-muted small"><i class="bi bi-collection me-1"></i>Grupos</div>
        </div>
    </div>
</div>

{{-- Ações rápidas --}}
<div class="d-flex flex-wrap gap-2 mb-4">
    <a href="{{ route('ficheiros.criar') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-upload me-1"></i>Carregar ficheiro
    </a>
    <a href="{{ route('admin.utilizadores') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-people me-1"></i>Gerir utilizadores
    </a>
    <a href="{{ route('admin.grupos') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-collection me-1"></i>Gerir grupos
    </a>
</div>

{{-- Últimos ficheiros carregados --}}
<div class="card border-0 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-clock-history me-2"></i>Últimos ficheiros carregados</span>
        <a href="{{ route('ficheiros.listar') }}" class="btn btn-outline-secondary btn-sm">Ver todos</a>
    </div>
    <div class="card-body p-0">
        <table class="table mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Nome</th>
                    <th>Grupo</th>
                    <th>Carregado por</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ultimosFicheiros as $f)
                <tr>
                    <td class="small">
                        <i class="bi {{ $f->icone() }} me-2"></i>
                        {{ $f->nome_exibicao }}
                    </td>
                    <td><span class="badge bg-light text-dark small">{{ $f->grupo->nome ?? '-' }}</span></td>
                    <td class="small text-muted">{{ $f->autor->nome ?? '-' }}</td>
                    <td class="small text-muted">{{ $f->created_at->format('d/m/Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-3 small">Nenhum ficheiro carregado ainda.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@else

<div class="mb-4">
    <h5 class="fw-bold mb-0">Olá, {{ auth()->user()->nome }}!</h5>
    <p class="text-muted small">
        Tens acesso a <strong>{{ $totalFicheiros }}</strong> ficheiro(s) nos teus grupos.
    </p>
</div>

<div class="d-flex gap-2 mb-4">
    <a href="{{ route('ficheiros.listar') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-folder2-open me-1"></i>Ver todos os ficheiros
    </a>
    @if(auth()->user()->podeCarregarFicheiros())
    <a href="{{ route('ficheiros.criar') }}" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-upload me-1"></i>Carregar ficheiro
    </a>
    @endif
    @if(auth()->user()->temCargo('operador'))
    <a href="{{ route('meu-grupo.mostrar') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-people me-1"></i>Gerir o meu grupo
    </a>
    @endif
</div>

{{-- Últimos ficheiros disponíveis --}}
<div class="card border-0 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-clock-history me-2"></i>Ficheiros recentes disponíveis para ti</span>
        <a href="{{ route('ficheiros.listar') }}" class="btn btn-outline-secondary btn-sm">Ver todos</a>
    </div>
    <div class="card-body p-0">
        <table class="table mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Nome</th>
                    <th>Grupo</th>
                    <th>Data</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($ultimosFicheiros as $f)
                <tr>
                    <td class="small fw-semibold">
                        <i class="bi {{ $f->icone() }} me-2"></i>
                        {{ $f->nome_exibicao }}
                    </td>
                    <td><span class="badge bg-light text-dark small">{{ $f->grupo->nome ?? '-' }}</span></td>
                    <td class="small text-muted">{{ $f->created_at->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('ficheiros.descarregar', $f) }}"
                           class="btn btn-success btn-sm px-2" title="Descarregar">
                            <i class="bi bi-download"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-3 small">
                        Nenhum ficheiro disponível ainda.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection

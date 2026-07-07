@extends('modelos.aplicacao')
@section('titulo', 'Ficheiros - SafeDocs')
@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="fw-bold mb-0">Ficheiros</h5>
        <p class="text-muted small mb-0">{{ $ficheiros->total() }} ficheiro(s) encontrado(s)</p>
    </div>
    @if(auth()->user()->podeCarregarFicheiros())
    <a href="{{ route('ficheiros.criar') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus me-1"></i>Carregar ficheiro
    </a>
    @endif
</div>

{{-- Pesquisa e filtro --}}
<form method="GET" action="{{ route('ficheiros.listar') }}" class="mb-4">
    <div class="d-flex flex-wrap gap-2 align-items-center">
        <div class="input-group input-group-sm" style="max-width:300px">
            <input type="text" name="pesquisa" class="form-control"
                   placeholder="Pesquisar..." value="{{ $pesquisa ?? '' }}">
            <button class="btn btn-outline-secondary" type="submit">
                <i class="bi bi-search"></i>
            </button>
        </div>
        <select name="grupo_id" class="form-select form-select-sm" style="max-width:200px" onchange="this.form.submit()">
            <option value="">Todos os grupos</option>
            @foreach($grupos as $g)
                <option value="{{ $g->id }}" {{ $grupoFiltro == $g->id ? 'selected' : '' }}>
                    {{ $g->nome }}
                </option>
            @endforeach
        </select>
        @if($pesquisa || $grupoFiltro)
            <a href="{{ route('ficheiros.listar') }}" class="btn btn-outline-danger btn-sm">
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
                        <th>Nome</th>
                        <th>Grupo</th>
                        <th>Tamanho</th>
                        <th>Expiração</th>
                        <th>Data upload</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ficheiros as $f)
                    <tr class="{{ $f->estaExpirado() ? 'table-secondary' : '' }}">
                        <td class="small fw-semibold">
                            <i class="bi {{ $f->icone() }} me-2"></i>
                            {{ $f->nome_exibicao }}
                            @if($f->estaExpirado())
                                <span class="badge bg-secondary ms-1" title="Este ficheiro expirou">Expirado</span>
                            @endif
                        </td>
                        <td><span class="badge bg-light text-dark small">{{ $f->grupo->nome ?? '-' }}</span></td>
                        <td class="small text-muted">{{ $f->tamanhoFormatado() }}</td>
                        <td class="small text-muted">
                            @if($f->expira_em)
                                @if($f->estaExpirado())
                                    <span class="text-danger">
                                        <i class="bi bi-clock-history me-1"></i>
                                        {{ $f->expira_em->format('d/m/Y H:i') }}
                                    </span>
                                @else
                                    <span class="text-warning">
                                        <i class="bi bi-clock me-1"></i>
                                        {{ $f->expira_em->format('d/m/Y H:i') }}
                                    </span>
                                @endif
                            @else
                                <span class="text-success"><i class="bi bi-infinity me-1"></i>Sem limite</span>
                            @endif
                        </td>
                        <td class="small text-muted">{{ $f->created_at->format('d/m/Y') }}</td>
                        <td class="text-end">
                            <div class="d-flex gap-1 justify-content-end">
                                @if(!$f->estaExpirado())
                                <a href="{{ route('ficheiros.descarregar', $f) }}"
                                   class="btn btn-success btn-sm px-2" title="Descarregar">
                                    <i class="bi bi-download"></i>
                                </a>
                                @else
                                <button class="btn btn-secondary btn-sm px-2" disabled title="Ficheiro expirado">
                                    <i class="bi bi-download"></i>
                                </button>
                                @endif
                                @if(auth()->user()->podeEliminarFicheiros())
                                <form method="POST" action="{{ route('ficheiros.eliminar', $f) }}"
                                      onsubmit="return confirm('Eliminar este ficheiro?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm px-2" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4 small">
                            <i class="bi bi-folder2 fs-4 d-block mb-2"></i>
                            Nenhum ficheiro encontrado.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($ficheiros->hasPages())
<div class="mt-3">{{ $ficheiros->appends(request()->query())->links() }}</div>
@endif

@endsection

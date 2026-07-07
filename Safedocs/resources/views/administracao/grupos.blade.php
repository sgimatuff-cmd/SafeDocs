@extends('modelos.aplicacao')
@section('titulo', 'Grupos - SafeDocs')
@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="fw-bold mb-0">Gestão de Grupos</h5>
        <p class="text-muted small mb-0">{{ $grupos->count() }} grupo(s) criado(s)</p>
    </div>
</div>

{{-- Criar novo grupo --}}
<div class="card border-0 shadow-sm mb-4" style="max-width:420px">
    <div class="card-header"><i class="bi bi-plus-circle me-2"></i>Criar novo grupo</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.grupos.criar') }}" class="d-flex gap-2">
            @csrf
            <input type="text" name="nome"
                   class="form-control form-control-sm @error('nome') is-invalid @enderror"
                   placeholder="Nome do grupo (ex: Turma A)"
                   value="{{ old('nome') }}">
            @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <button type="submit" class="btn btn-primary btn-sm text-nowrap">
                <i class="bi bi-plus me-1"></i>Criar
            </button>
        </form>
    </div>
</div>

{{-- Lista de grupos --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Nome do Grupo</th>
                    <th class="text-center">Utilizadores</th>
                    <th class="text-center">Ficheiros</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($grupos as $g)
                <tr>
                    <td class="small fw-semibold">
                        <i class="bi bi-collection me-2 text-muted"></i>{{ $g->nome }}
                        @if($g->nome === 'Grupo Geral')
                            <span class="badge bg-light text-muted small ms-1">padrão</span>
                        @endif
                    </td>
                    <td class="text-center small text-muted">{{ $g->utilizadores_count }}</td>
                    <td class="text-center small text-muted">{{ $g->ficheiros_count }}</td>
                    <td class="text-end">
                        @if($g->nome !== 'Grupo Geral')
                        <form method="POST" action="{{ route('admin.grupos.eliminar', $g) }}"
                              onsubmit="return confirm('Eliminar o grupo {{ $g->nome }}? Os ficheiros do grupo também serão eliminados.')">
                            @csrf @method('DELETE')
                            <button class="btn btn-outline-danger btn-sm" title="Eliminar grupo">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                        @else
                        <span class="text-muted small">Protegido</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

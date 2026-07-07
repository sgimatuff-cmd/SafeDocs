@extends('modelos.aplicacao')
@section('titulo', 'O meu grupo - SafeDocs')
@section('content')

<div class="mb-4">
    <h5 class="fw-bold mb-0">O meu grupo</h5>
    <p class="text-muted small mb-0">
        Como operador, és responsável pelo(s) teu(s) grupo(s): podes adicionar ou remover membros.
    </p>
</div>

@forelse($grupos as $grupo)
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="bi bi-collection me-2"></i>{{ $grupo->nome }}</span>
        <a href="{{ route('ficheiros.listar', ['grupo_id' => $grupo->id]) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-folder2-open me-1"></i>{{ $grupo->ficheiros_count }} ficheiro(s)
        </a>
    </div>
    <div class="card-body">
        <p class="small text-muted fw-semibold mb-2">Membros ({{ $grupo->utilizadores->count() }})</p>
        <div class="table-responsive mb-3">
            <table class="table table-sm mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($grupo->utilizadores as $membro)
                    <tr>
                        <td class="small fw-semibold">
                            {{ $membro->nome }}
                            @if($membro->id === auth()->id())
                                <span class="text-muted fw-normal">(tu)</span>
                            @endif
                        </td>
                        <td class="small text-muted">{{ $membro->email }}</td>
                        <td class="text-end">
                            @if($grupo->nome !== 'Grupo Geral' && $membro->id !== auth()->id())
                            <form action="{{ route('meu-grupo.membros.remover', [$grupo, $membro]) }}" method="POST"
                                  onsubmit="return confirm('Remover {{ $membro->nome }} do grupo {{ $grupo->nome }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm" title="Remover do grupo">
                                    <i class="bi bi-person-dash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-muted small text-center py-2">Sem membros.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <form action="{{ route('meu-grupo.membros.adicionar', $grupo) }}" method="POST" class="d-flex gap-2" style="max-width:420px">
            @csrf
            <input type="email" name="email" class="form-control form-control-sm"
                   placeholder="Email do utilizador a adicionar" required>
            <button type="submit" class="btn btn-primary btn-sm text-nowrap">
                <i class="bi bi-person-plus me-1"></i>Adicionar
            </button>
        </form>
    </div>
</div>
@empty
<div class="card border-0 shadow-sm">
    <div class="card-body text-center text-muted py-4 small">
        Ainda não pertences a nenhum grupo.
    </div>
</div>
@endforelse

@endsection

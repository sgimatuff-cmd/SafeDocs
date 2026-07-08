@extends('modelos.aplicacao')
@section('titulo', 'Utilizadores - SafeDocs')
@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="fw-bold mb-0">Gestão de Utilizadores</h5>
        <p class="text-muted small mb-0">{{ $utilizadores->total() }} utilizador(es) registado(s)</p>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Cargos</th>
                        <th>Grupos</th>
                        <th>Membro desde</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($utilizadores as $u)
                    <tr>
                        <td class="small fw-semibold">
                            {{ $u->nome }}
                            @if($u->id === auth()->id())
                                <span class="text-muted fw-normal">(você)</span>
                            @endif
                        </td>
                        <td class="small text-muted">{{ $u->email }}</td>
                        <td>
                            <div class="d-flex flex-wrap gap-1 mb-1">
                                @forelse($u->cargos as $c)
                                    <span class="badge {{ $c->corBadge() }} small d-flex align-items-center gap-1">
                                        {{ $c->nome }}
                                        @if($u->id !== auth()->id() || $c->slug !== 'admin')
                                        <form action="{{ route('admin.utilizadores.cargos.remover', [$u, $c]) }}"
                                              method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn p-0 border-0 bg-transparent text-white"
                                                    style="font-size:.7rem; line-height:1"
                                                    title="Remover cargo"
                                                    onclick="return confirm('Remover cargo {{ $c->nome }} de {{ $u->nome }}?')">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </span>
                                @empty
                                    <span class="text-muted small">Sem cargos</span>
                                @endforelse
                            </div>
                            @php $cargosDisponiveis = $cargos->filter(fn($c) => !$u->cargos->contains($c->id) && $c->slug !== 'admin'); @endphp
                            @if($u->id !== auth()->id() && $cargosDisponiveis->isNotEmpty())
                            <form action="{{ route('admin.utilizadores.cargos.adicionar', $u) }}" method="POST">
                                @csrf
                                <select name="cargo_id" class="form-select form-select-sm" style="width:110px"
                                        onchange="this.form.submit()">
                                    <option selected disabled>+ cargo</option>
                                    @foreach($cargosDisponiveis as $c)
                                        <option value="{{ $c->id }}">{{ $c->nome }}</option>
                                    @endforeach
                                </select>
                            </form>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($u->grupos as $g)
                                    <span class="badge bg-light text-dark small d-flex align-items-center gap-1">
                                        {{ $g->nome }}
                                        @if($g->nome !== 'Grupo Geral' && $u->id !== auth()->id())
                                        <form action="{{ route('admin.utilizadores.grupo.remover', [$u, $g]) }}"
                                              method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn p-0 border-0 bg-transparent text-danger"
                                                    style="font-size:.7rem; line-height:1"
                                                    title="Remover do grupo"
                                                    onclick="return confirm('Remover {{ $u->nome }} do grupo {{ $g->nome }}?')">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="small text-muted">{{ $u->created_at->format('d/m/Y') }}</td>
                        <td class="text-end">
                            <div class="d-flex gap-1 justify-content-end align-items-center flex-wrap">

                                @if($u->id !== auth()->id())
                                <form action="{{ route('admin.utilizadores.grupo.adicionar', $u) }}" method="POST">
                                    @csrf
                                    <select name="grupo_id" class="form-select form-select-sm" style="width:110px"
                                            onchange="this.form.submit()">
                                        <option selected disabled>+ grupo</option>
                                        @foreach(\App\Models\Grupo::all() as $g)
                                            <option value="{{ $g->id }}">{{ $g->nome }}</option>
                                        @endforeach
                                    </select>
                                </form>
                                @endif

                                @if(!$u->e_administrador)
                                <form id="promover-form-{{ $u->id }}"
                                      action="{{ route('admin.utilizadores.promover', $u) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="password_confirmacao" id="promover-pass-{{ $u->id }}">
                                    <button type="button" class="btn btn-outline-success btn-sm" title="Promover a Admin"
                                            onclick="var p = prompt('Introduz a tua password para promover {{ $u->nome }} a administrador:'); if (p) { document.getElementById('promover-pass-{{ $u->id }}').value = p; document.getElementById('promover-form-{{ $u->id }}').submit(); }">
                                        <i class="bi bi-shield-check"></i>
                                    </button>
                                </form>
                                @endif

                                @if($u->id !== auth()->id())
                                <form action="{{ route('admin.utilizadores.eliminar', $u) }}" method="POST"
                                      onsubmit="return confirm('Remover o utilizador {{ $u->nome }}?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm" title="Remover">
                                        <i class="bi bi-person-x"></i>
                                    </button>
                                </form>
                                @endif

                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($utilizadores->hasPages())
<div class="mt-3">{{ $utilizadores->links() }}</div>
@endif

@endsection
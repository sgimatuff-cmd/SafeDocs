@extends('layouts.app')
@section('titulo', 'Carregar Ficheiro — SafeDocs')
@section('content')

<div class="mb-4">
    <h5 class="fw-bold mb-0">Carregar Ficheiro</h5>
    <p class="text-muted small">O ficheiro ficará visível para todos os membros do grupo selecionado.</p>
</div>

<div class="card border-0 shadow-sm" style="max-width:560px">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('ficheiros.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label small fw-semibold">Nome para aparecer no site</label>
                <input type="text" name="nome_exibicao"
                       class="form-control @error('nome_exibicao') is-invalid @enderror"
                       placeholder="Ex: Manual de Português"
                       value="{{ old('nome_exibicao') }}">
                @error('nome_exibicao')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label small fw-semibold">Grupo</label>
                <select name="grupo_id" class="form-select @error('grupo_id') is-invalid @enderror">
                    <option value="">— Seleciona um grupo —</option>
                    @foreach($grupos as $g)
                        <option value="{{ $g->id }}" {{ old('grupo_id') == $g->id ? 'selected' : '' }}>
                            {{ $g->nome }}
                        </option>
                    @endforeach
                </select>
                @error('grupo_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- DATA DE EXPIRAÇÃO --}}
            <div class="mb-3">
                <label class="form-label small fw-semibold">
                    Data de expiração
                    <span class="text-muted fw-normal">(opcional)</span>
                </label>
                <input type="datetime-local" name="expira_em"
                       class="form-control @error('expira_em') is-invalid @enderror"
                       value="{{ old('expira_em') }}"
                       min="{{ now()->format('Y-m-d\TH:i') }}">
                <div class="form-text">Se definires uma data, o ficheiro deixa de estar disponível depois dessa data.</div>
                @error('expira_em')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-4">
                <label class="form-label small fw-semibold">Ficheiro</label>
                <input type="file" name="ficheiro"
                       class="form-control @error('ficheiro') is-invalid @enderror">
                <div class="form-text">Máximo 100MB.</div>
                @error('ficheiro')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="bi bi-upload me-1"></i>Carregar
                </button>
                <a href="{{ route('ficheiros.index') }}" class="btn btn-outline-secondary btn-sm">Cancelar</a>
            </div>
        </form>
    </div>
</div>

@endsection

@extends('layouts.app')
@section('titulo', 'Painel — SafeDocs')
@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="fw-bold mb-0">Bem-vindo, {{ auth()->user()->nome }}</h5>
        <p class="text-muted small mb-0">Aceda e transfira os documentos disponíveis</p>
    </div>
    <a href="{{ route('ficheiros.index') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-files me-1"></i>Ver ficheiros
    </a>
</div>

<div class="card">
    <div class="card-header">Transferências recentes</div>
    <div class="card-body p-0">
        @if($meusDownloads->count())
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead><tr><th>Ficheiro</th><th>Data</th></tr></thead>
                    <tbody>
                        @foreach($meusDownloads as $dl)
                        <tr>
                            <td class="small">{{ $dl->ficheiro->nome_original ?? 'Ficheiro removido' }}</td>
                            <td class="small text-muted">{{ $dl->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox" style="font-size:2rem"></i>
                <p class="mt-2 small mb-3">Ainda não transferiu nenhum ficheiro.</p>
                <a href="{{ route('ficheiros.index') }}" class="btn btn-primary btn-sm">Ver ficheiros disponíveis</a>
            </div>
        @endif
    </div>
</div>
@endsection

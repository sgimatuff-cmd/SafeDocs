@extends('modelos.convidado')
@section('titulo', 'Sessão terminada - SafeDocs')
@section('content')
<div class="container">
    <div class="auth-card card mx-auto text-center">
        <div class="card-body p-4 p-md-5">
            <i class="bi bi-check-circle" style="font-size:2.5rem;color:#444"></i>
            <h4 class="fw-bold mt-3 mb-1">Sessão terminada</h4>
            <p class="text-muted small mb-4">A sua sessão foi encerrada com segurança.</p>
            <div class="d-flex gap-2 justify-content-center">
                <a href="{{ route('entrar') }}" class="btn btn-primary px-4">Entrar novamente</a>
                <a href="{{ route('inicio') }}" class="btn btn-outline-secondary px-4">Página inicial</a>
            </div>
        </div>
    </div>
</div>
@endsection

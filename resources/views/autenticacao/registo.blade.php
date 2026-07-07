@extends('modelos.convidado')
@section('titulo', 'Registar - SafeDocs')
@section('content')
<div class="container">
    <div class="auth-card card mx-auto">
        <div class="card-body p-4 p-md-5">
            <div class="mb-4">
                <h4 class="fw-bold mb-1">Criar conta</h4>
                <p class="text-muted small mb-0">Registe-se no SafeDocs</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger mb-3">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $erro)
                            <li>{{ $erro }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('registar') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Nome completo</label>
                    <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror"
                           value="{{ old('nome') }}" placeholder="O seu nome" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" placeholder="email@empresa.com" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Palavra-passe</label>
                    <input type="password" name="palavra_passe" class="form-control" placeholder="Mínimo 8 caracteres" required>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold small">Confirmar palavra-passe</label>
                    <input type="password" name="palavra_passe_confirmation" class="form-control" placeholder="Repita a palavra-passe" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2">Criar conta</button>
            </form>

            <hr class="my-4">
            <p class="text-center text-muted small mb-0">
                Já tem conta? <a href="{{ route('entrar') }}" class="text-dark fw-semibold">Entrar</a>
            </p>
        </div>
    </div>
</div>
@endsection

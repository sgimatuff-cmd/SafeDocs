@extends('layouts.guest')
@section('titulo', 'Entrar — SafeDocs')
@section('content')
<div class="container">
    <div class="auth-card card mx-auto">
        <div class="card-body p-4 p-md-5">
            <div class="mb-4">
                <h4 class="fw-bold mb-1">Iniciar sessão</h4>
                <p class="text-muted small mb-0">Bem-vindo ao SafeDocs</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger mb-3">{{ $errors->first() }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger mb-3">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" placeholder="email@empresa.com" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Palavra-passe</label>
                    <input type="password" name="palavra_passe" class="form-control" placeholder="••••••••" required>
                </div>
                <div class="mb-4">
                    <div class="form-check">
                        <input type="checkbox" name="lembrar" class="form-check-input" id="lembrar">
                        <label class="form-check-label small text-muted" for="lembrar">Manter sessão iniciada</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2">Entrar</button>
            </form>

            <div class="position-relative my-4">
                <hr>
                <span class="position-absolute top-50 start-50 translate-middle bg-white px-2 text-muted small">ou</span>
            </div>

            <a href="{{ route('google.redirect') }}" class="btn btn-outline-secondary w-100 py-2">
                <i class="bi bi-google me-2"></i>Continuar com Google
            </a>

            <hr class="my-4">
            <p class="text-center text-muted small mb-0">
                Não tem conta? <a href="{{ route('register') }}" class="text-dark fw-semibold">Registar</a>
            </p>
        </div>
    </div>
</div>
@endsection

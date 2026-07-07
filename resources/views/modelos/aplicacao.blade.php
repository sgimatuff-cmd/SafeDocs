<!DOCTYPE html>
<html lang="pt" id="html-raiz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('titulo', 'SafeDocs')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <script>
        if (localStorage.getItem('tema') === 'escuro') {
            document.getElementById('html-raiz').classList.add('tema-escuro');
        }
    </script>
</head>
<body class="d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('painel') }}">
            <i class="bi bi-shield-lock me-2"></i>Safe<span>Docs</span>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav me-auto ms-4">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('painel') ? 'active fw-bold' : '' }}"
                       href="{{ route('painel') }}">Painel</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('ficheiros.*') ? 'active fw-bold' : '' }}"
                       href="{{ route('ficheiros.listar') }}">Ficheiros</a>
                </li>
                @if(auth()->user()->temCargo('operador') && !auth()->user()->eAdmin())
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('meu-grupo.*') ? 'active fw-bold' : '' }}"
                           href="{{ route('meu-grupo.mostrar') }}">O meu grupo</a>
                    </li>
                @endif
                @if(auth()->user()->podeGerirUtilizadores())
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.utilizadores*') ? 'active fw-bold' : '' }}"
                           href="{{ route('admin.utilizadores') }}">Utilizadores</a>
                    </li>
                @endif
                @if(auth()->user()->podeGerirGrupos())
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.grupos*') ? 'active fw-bold' : '' }}"
                           href="{{ route('admin.grupos') }}">Grupos</a>
                    </li>
                @endif
                @if(auth()->user()->podeVerLogs())
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.logs*') ? 'active fw-bold' : '' }}"
                           href="{{ route('admin.logs') }}">Logs</a>
                    </li>
                @endif
            </ul>
            <ul class="navbar-nav ms-auto align-items-center gap-2">
                <li class="nav-item">
                    <span class="nav-link small" style="color:#888 !important">
                        {{ auth()->user()->nome }}
                        @foreach(auth()->user()->cargos as $cargo)
                            <span class="badge {{ $cargo->corBadge() }} ms-1">{{ $cargo->nome }}</span>
                        @endforeach
                    </span>
                </li>

                <li class="nav-item">
                    <button class="btn-tema" id="btn-tema" title="Alternar tema">
                        <i class="bi bi-moon-fill" id="icone-tema"></i>
                    </button>
                </li>

                <li class="nav-item">
                    <form method="POST" action="{{ route('sair') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm">Sair</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="container my-4 flex-grow-1">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4 small">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4 small">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @yield('content')
</main>

<footer>
    <div class="container">
        <i class="bi bi-shield-lock me-2"></i>SafeDocs &copy; {{ date('Y') }} - Arquivo Empresarial Seguro
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const html      = document.getElementById('html-raiz');
    const btnTema   = document.getElementById('btn-tema');
    const iconeTema = document.getElementById('icone-tema');

    function atualizarIcone() {
        const temaEscuro = html.classList.contains('tema-escuro');
        iconeTema.className = temaEscuro ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
    }

    btnTema.addEventListener('click', function () {
        const temaEscuro = html.classList.toggle('tema-escuro');
        localStorage.setItem('tema', temaEscuro ? 'escuro' : 'claro');
        atualizarIcone();
    });

    atualizarIcone();
</script>

@yield('scripts')
</body>
</html>

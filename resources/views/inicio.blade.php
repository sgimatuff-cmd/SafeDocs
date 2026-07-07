<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SafeDocs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ route('inicio') }}">
                Safe<span>Docs</span>
            </a>
            <div class="ms-auto d-flex gap-2">
                @auth
                    <a href="{{ route('painel') }}" class="btn btn-outline-light btn-sm">Painel</a>
                @else
                    <a href="{{ route('entrar') }}" class="btn btn-outline-light btn-sm">Entrar</a>
                    <a href="{{ route('registar') }}" class="btn btn-light btn-sm fw-semibold">Registar</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Cabeçalho --}}
    <section class="hero">
        <div class="container">
            <h1>SafeDocs</h1>
            <p class="mt-3 mb-4">
                Arquivo interno de documentos, organizado por grupos e com registo de acessos.
            </p>
            @auth
                <a href="{{ route('painel') }}" class="btn btn-light fw-semibold px-4">
                    Ir para o painel
                </a>
            @else
                <div class="d-flex gap-3">
                    <a href="{{ route('entrar') }}" class="btn btn-light fw-semibold px-4">Entrar</a>
                    <a href="{{ route('registar') }}" class="btn btn-outline-light px-4">Criar conta</a>
                </div>
            @endauth
        </div>
    </section>

    {{-- Funcionamento --}}
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <p class="fw-semibold mb-1">Acesso por grupo</p>
                    <p class="text-muted mb-0" style="font-size:.88rem;line-height:1.7">Cada utilizador só vê os ficheiros dos grupos a que pertence.</p>
                </div>
                <div class="col-md-4">
                    <p class="fw-semibold mb-1">Registo de auditoria</p>
                    <p class="text-muted mb-0" style="font-size:.88rem;line-height:1.7">Uploads, downloads e alterações de permissões ficam registados.</p>
                </div>
                <div class="col-md-4">
                    <p class="fw-semibold mb-1">Permissões por cargo</p>
                    <p class="text-muted mb-0" style="font-size:.88rem;line-height:1.7">Utilizador, operador e administrador têm níveis de acesso diferentes.</p>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            SafeDocs &copy; {{ date('Y') }} - Braga, Portugal
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

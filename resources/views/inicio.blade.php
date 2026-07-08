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

    {{-- Sobre nós --}}
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row g-5 align-items-start">
                <div class="col-md-5">
                    <p class="text-muted mb-1" style="font-size:.78rem;letter-spacing:.06em;text-transform:uppercase">Sobre nós</p>
                    <h2 class="fw-bold mb-0" style="font-size:1.6rem;letter-spacing:-.5px;line-height:1.2">
                        Uma plataforma feita para quem leva os seus documentos a sério.
                    </h2>
                </div>
                <div class="col-md-6 offset-md-1">
                    <p style="color:#555;line-height:1.85;font-size:.97rem">
                        Na SafeDocs centralizamos a documentação interna da sua organização. Cada ficheiro tem um responsável, cada acesso fica registado, e cada colaborador vê apenas o que precisa de ver.
                    </p>
                    <p style="color:#555;line-height:1.85;font-size:.97rem" class="mb-0">
                        Não inventámos a roda. Apenas fizemos com que guardar e partilhar documentos internos deixasse de ser uma dor de cabeça.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Separador --}}
    <div style="height:1px;background:var(--borda)"></div>

    {{-- Funcionamento --}}
    <section class="py-5" style="background:#f9f9f9">
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

    {{-- CTA --}}
    <section class="py-5 mt-auto" style="background:var(--preto);color:#fff">
        <div class="container d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <p class="fw-semibold mb-1" style="font-size:1.05rem">A sua organização já usa a SafeDocs?</p>
                <p class="mb-0" style="color:#888;font-size:.88rem">Entre ou crie uma conta para começar.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('entrar') }}" class="btn btn-light fw-semibold px-4">Entrar</a>
                <a href="{{ route('registar') }}" class="btn btn-outline-light px-4">Registar</a>
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
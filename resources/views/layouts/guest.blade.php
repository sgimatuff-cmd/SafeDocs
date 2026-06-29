<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('titulo', 'SafeDocs')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100" style="background:#f7f7f7">
    <nav class="navbar navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('inicio') }}">
                <i class="bi bi-shield-lock me-2"></i>Safe<span>Docs</span>
            </a>
        </div>
    </nav>
    <main class="flex-grow-1">@yield('content')</main>
    <footer>
        <div class="container">
            <i class="bi bi-shield-lock me-2"></i>SafeDocs &copy; {{ date('Y') }} — Arquivo Empresarial Seguro
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

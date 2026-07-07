<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso negado - SafeDocs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100" style="background:#f7f7f7">
    <div class="text-center p-5">
        <h1 class="fw-bold" style="font-size:4rem;color:#ccc">403</h1>
        <h4 class="fw-bold mb-2">Acesso negado</h4>
        <p class="text-muted mb-4">Não tem permissão para aceder a esta página.</p>
        <a href="{{ route('painel') }}" class="btn btn-primary px-4">Voltar ao painel</a>
    </div>
</body>
</html>

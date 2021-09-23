<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <title>VetClin System - Clínica Veterinária</title>
</head>
<body>
    <header class="container-fluid" style="background-color: #388e3c;">
        <div class="container">
            <nav class="navbar navbar-expand-md navbar-dark">
                <div class="container-fluid">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Alternar navegação">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbar">
                        <a class="navbar-brand" href="">VetClin System</a>
                        <ul class="navbar-nav me-auto mb-2 mt-2 mb-md-0 mt-md-0">
                            <li class="nav-item">
                                <a class="nav-link @if(($page ?? '') == 'cliente'){{ 'active' }}@endif" href="{{ route('cliente.index') }}">Clientes</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link @if(($page ?? '') == 'veterinario'){{ 'active' }}@endif" href="{{ route('veterinario.index') }}">Veterinários</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link @if(($page ?? '') == 'especialidade'){{ 'active' }}@endif" href="{{ route('especialidade.index') }}">Especialidades</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link @if(($page ?? '') == 'pet'){{ 'active' }}@endif" href="">Pets</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link @if(($page ?? '') == 'raca'){{ 'active' }}@endif" href="">Raças</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </header>
    <div class="container p-4">
        <div class="card shadow-sm">
            <div class="card-header text-white d-flex align-items-center" style="background-color: #388e3c;">{{ $title }}<i class="bi bi-{{ $icon }} ms-auto fs-4"></i></div>
            <div class="card-body">
                @yield('content')
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>

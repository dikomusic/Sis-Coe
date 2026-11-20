<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Consultas - SIS COE')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: #e2e8f0;
            min-height: 100vh;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }
        .container-custom {
            max-width: 1200px;
        }
        .card-consulta {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
        }
        .table-dark {
            --bs-table-bg: rgba(30, 40, 56, 0.6);
            --bs-table-color: #e2e8f0;
        }
        .btn-outline-fucsia {
            color: var(--fucsia);
            border-color: var(--fucsia);
        }
        .btn-outline-fucsia:hover {
            background-color: var(--fucsia);
            color: white;
        }
    </style>
</head>
<body>
    <div class="container container-custom py-5">
        <div class="text-center mb-5">
            <h1 class="fw-bold text-white">
                <span class="text-fucsia">🪖</span> Consultas - SIS COE
            </h1>
            <p class="text-muted">Modo Invitado - Solo lectura</p>
        </div>

        @yield('content')

        <div class="text-center mt-5 text-muted small">
            © {{ date('Y') }} SIS - COE | Sistema Institucional
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
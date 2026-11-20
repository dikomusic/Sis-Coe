<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Detalle Credencial - SIS COE')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            background: #0f172a;
            color: #e2e8f0;
            font-family: 'Segoe UI', system-ui, sans-serif;
            min-height: 100vh;
            padding: 2rem;
        }
        .card-detail {
            background: rgba(30, 40, 56, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(227, 0, 82, 0.2);
            border-radius: 16px;
            max-width: 800px;
            margin: 0 auto;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
        .header-info {
            background: linear-gradient(135deg, var(--fucsia) 0%, #b80042 100%);
            border-radius: 16px 16px 0 0;
            padding: 1.5rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
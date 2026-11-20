<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Sistema Institucional')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f9;
        }
        .sidebar {
            min-height: 100vh;
            background-color: #0f172a;
        }
        .sidebar h6 {
            color: #94a3b8;
            font-size: 0.8rem;
            text-transform: uppercase;
            padding: 10px 15px;
        }
        .sidebar a {
            color: #e2e8f0;
            text-decoration: none;
            padding: 10px 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .sidebar a:hover {
            background-color: #1e293b;
        }
        .submenu a {
            padding-left: 40px;
            font-size: 0.95rem;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">

        <!-- MENÚ LATERAL -->
        <nav class="col-md-2 sidebar p-0">

            <div class="text-center text-white py-3 border-bottom">
                <strong>🪖 SIS - COE</strong>
            </div>

            <a href="{{ route('dashboard') }}">
                <i class="bi bi-house"></i> Inicio
            </a>

            <h6>Registros</h6>

            <!-- ASOCIADOS -->
            <a data-bs-toggle="collapse" href="#asociadosMenu">
                <i class="bi bi-person-badge"></i> Registro de Asociados
            </a>
            <div class="collapse submenu" id="asociadosMenu">
                <a href="{{ route('socio.index') }}">Registro Titular</a>
                <a href="{{ route('beneficiarios.create') }}">Registro Beneficiario</a>
            </div>

            <!-- AGREGADOS -->
            <a data-bs-toggle="collapse" href="#agregadosMenu">
                <i class="bi bi-shield-check"></i> Agregados Militares
            </a>
            <div class="collapse submenu" id="agregadosMenu">
                <a href="#">Registro Titular</a>
                <a href="#">Registro Beneficiario</a>
            </div>

            <!-- INVITADOS -->
            <a data-bs-toggle="collapse" href="#invitadosMenu">
                <i class="bi bi-person"></i> Invitados
            </a>
            <div class="collapse submenu" id="invitadosMenu">
                <a href="#">Registro Titular</a>
                <a href="#">Registro Beneficiario</a>
            </div>

            <h6>Gestión</h6>

           

            <h6>Consulta</h6>

            <a data-bs-toggle="collapse" href="#consultaMenu">
                <i class="bi bi-search"></i> Consultas
            </a>
            <div class="collapse submenu" id="consultaMenu">
                <a href="{{ route('consulta.socio') }}">Por Socio Titular</a>
                <a href="{{ route('consulta.beneficiario') }}">Por Beneficiario</a>
            </div>

        </nav>

        <!-- CONTENIDO -->
        <main class="col-md-10 p-4">
            @yield('content')
        </main>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

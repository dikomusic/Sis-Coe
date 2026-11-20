<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIS - COE | Sistema Institucional')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --fucsia: #e30052;
            --fucsia-dark: #b80042;
            --sidebar-bg: #1d2838;
            --sidebar-hover: rgba(227, 0, 82, 0.09);
            --text-muted: #94a3b8;
        }

        body {
            background-color: #f4f6f9;
            color: #1e293b;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            min-height: 100vh;
        }

        .sidebar {
            min-height: 100vh;
            background-color: var(--sidebar-bg);
            border-right: 1px solid #1e2533;
            box-shadow: 3px 0 20px rgba(0,0,0,0.25);
            color: #e2e8f0;
        }

        .sidebar-header {
            padding: 1.8rem 1.5rem;
            background: linear-gradient(135deg, rgba(227,0,82,0.18) 0%, transparent 70%);
            border-bottom: 1px solid rgba(227,0,82,0.15);
            text-align: center;
        }

        .sidebar-header strong {
            font-size: 1.4rem;
            letter-spacing: 1.8px;
            background: linear-gradient(90deg, #ffffff, var(--fucsia), #ffffff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-size: 200% auto;
            animation: shine 10s linear infinite;
        }

        @keyframes shine {
            to { background-position: 200% center; }
        }

        .user-profile {
            padding: 1.2rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            background: rgba(30, 58, 138, 0.08);
        }

        .logout-btn {
            background: rgba(220, 53, 69, 0.15);
            color: #dc3545;
            border: 1px solid rgba(220, 53, 69, 0.3);
        }

        .logout-btn:hover {
            background: rgba(220, 53, 69, 0.25);
        }

        .sidebar-section {
            padding: 1.5rem 1.5rem 0.6rem;
            color: var(--text-muted);
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 1.1px;
            text-transform: uppercase;
        }

        .nav-link {
            color: #cbd5e1;
            padding: 0.9rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 12px;
            border-left: 4px solid transparent;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .nav-link:hover,
        .nav-link.active {
            background: var(--sidebar-hover);
            color: white;
            border-left-color: var(--fucsia);
        }

        .nav-link i {
            font-size: 1.4rem;
            width: 28px;
            text-align: center;
            transition: transform 0.35s ease, color 0.3s ease;
        }

        .nav-link:hover i {
            transform: scale(1.18);
            color: var(--fucsia);
        }

        .submenu .nav-link {
            padding-left: 3.9rem;
            font-size: 0.97rem;
            color: #a0aec0;
        }

        .submenu .nav-link:hover {
            color: white;
            background: rgba(227, 0, 82, 0.06);
        }

        main {
            background-color: #f4f6f9;
            padding: 2.5rem 2rem;
        }

        .sidebar::-webkit-scrollbar {
            width: 5px;
        }
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(227, 0, 82, 0.35);
            border-radius: 10px;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row g-0">

        <nav class="col-md-2 sidebar overflow-auto">

            <div class="sidebar-header">
                <strong>🪖 SIS - COE</strong>
            </div>

            <!-- Perfil del usuario logueado -->
            @auth
            <div class="user-profile">
                <div class="d-flex align-items-center">
                    <img src="{{ auth()->user()->foto ? asset('storage/' . auth()->user()->foto) : asset('images/default-avatar.jpg') }}"
                         alt="Perfil" class="rounded-circle me-3" width="52" height="52"
                         style="object-fit: cover; border: 2px solid var(--fucsia);">
                    <div>
                        <h6 class="mb-0 fw-bold text-white">{{ auth()->user()->name }}</h6>
                        <small class="text-muted">{{ auth()->user()->email }}</small>
                    </div>
                </div>

                <!-- Botón Cerrar Sesión -->
                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit" class="btn logout-btn w-100 d-flex align-items-center justify-content-center">
                        <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
                    </button>
                </form>
            </div>
            @endauth
<div class="bg-dark text-white p-3 text-center">
    Usuario logueado: {{ auth()->user()->name ?? 'No logueado' }}  
    Rol: {{ auth()->user()->rol ?? 'Sin rol' }}
</div>
            <!-- Menú según rol -->
            @php
                $rol = auth()->user()->rol ?? null;
            @endphp

            <div class="pt-2">
                <!-- Inicio (visible para todos los roles autenticados) -->


                <!-- Gestión de Usuarios → SOLO ADMIN -->
                @if($rol === 'ADMIN')

                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-house-door-fill"></i>
                    Inicio
                </a>

                <div class="sidebar-section">Gestión de Usuarios</div>

                <a class="nav-link dropdown-toggle {{ request()->routeIs('usuarios.*') ? 'active' : '' }}"
                   data-bs-toggle="collapse" href="#usuariosMenu" role="button">
                    <i class="bi bi-people-fill"></i>
                    Gestión de Usuarios
                </a>
                <div class="collapse submenu {{ request()->routeIs('usuarios.*') ? 'show' : '' }}" id="usuariosMenu">
                    <a href="{{ route('usuarios.create', 'ADMIN') }}" class="nav-link">
                        <i class="bi bi-shield-lock-fill"></i> Administrador
                    </a>
                    <a href="{{ route('usuarios.create', 'USUARIO') }}" class="nav-link">
                        <i class="bi bi-person-check-fill"></i> Usuario
                    </a>
                    <a href="{{ route('usuarios.create', 'INVITADO') }}" class="nav-link">
                        <i class="bi bi-person-fill"></i> Invitado
                    </a>
                </div>
                @endif

                <!-- Registros → SOLO ADMIN y USUARIO -->
                @if($rol === 'ADMIN' || $rol === 'USUARIO')
                <div class="sidebar-section">Registros</div>

                <a class="nav-link dropdown-toggle {{ request()->routeIs('socio.*|beneficiarios.*') ? 'active' : '' }}"
                   data-bs-toggle="collapse" href="#asociadosMenu" role="button">
                    <i class="bi bi-person-badge-fill"></i>
                    Asociados
                </a>
                <div class="collapse submenu @if(request()->routeIs('socio.*|beneficiarios.*')) show @endif" id="asociadosMenu">
                    <a href="{{ route('admin.socio.index') }}" class="nav-link">Registro Titular</a>
                    <a href="{{ route('beneficiarios.create') }}" class="nav-link">Registro Beneficiario</a>
                </div>

                <!-- Agregados e Invitados (solo ADMIN) -->
                @if($rol === 'ADMIN')
                <a class="nav-link dropdown-toggle" data-bs-toggle="collapse" href="#agregadosMenu" role="button">
                    <i class="bi bi-shield-check"></i> Agregados Militares
                </a>
                <div class="collapse submenu" id="agregadosMenu">
                    <a href="#" class="nav-link">Registro Titular</a>
                    <a href="#" class="nav-link">Registro Beneficiario</a>
                </div>

                <a class="nav-link dropdown-toggle" data-bs-toggle="collapse" href="#invitadosMenu" role="button">
                    <i class="bi bi-person-fill"></i> Invitados
                </a>
                <div class="collapse submenu" id="invitadosMenu">
                    <a href="#" class="nav-link">Registro Titular</a>
                    <a href="#" class="nav-link">Registro Beneficiario</a>
                </div>
                @endif
                @endif

                <!-- Consultas → Visible para TODOS (incluyendo INVITADO) -->
                <div class="sidebar-section">Consultas</div>
                <a class="nav-link dropdown-toggle" data-bs-toggle="collapse" href="#consultaMenu" role="button">
                    <i class="bi bi-search"></i>
                    Consultas
                </a>
                <div class="collapse submenu" id="consultaMenu">
                    <a href="{{ route('consultas.index') }}" class="nav-link">Credencial Digital</a>
                </div>
                <div class="collapse submenu" id="consultaMenu">
                    <a href="{{ route('consultas.search') }}" class="nav-link">Socio Titular/Beneficiario</a>
                </div>
                <div class="collapse submenu" id="consultaMenu">
                    <a href="{{ route('consultas.index') }}" class="nav-link">Agregados/Invitados</a>
                </div>
            </div>

        </nav>

        <!-- Contenido principal -->
        <main class="col-md-10">
            @yield('content')
        </main>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
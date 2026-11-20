<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Iniciar Sesión - SIS COE')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: white;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.37);
            max-width: 420px;
            width: 100%;
        }
        .card-header {
            background: var(--fucsia);
            border-radius: 16px 16px 0 0;
            text-align: center;
            padding: 1.5rem;
        }
        .btn-primary {
            background-color: var(--fucsia);
            border-color: var(--fucsia);
        }
        .btn-primary:hover {
            background-color: var(--fucsia-dark);
            border-color: var(--fucsia-dark);
        }
        .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
        }
        .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: var(--fucsia);
            box-shadow: 0 0 0 0.25rem rgba(227, 0, 82, 0.25);
            color: white;
        }
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }
        .text-muted {
            color: rgba(255, 255, 255, 0.6) !important;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-5">
                <div class="text-center mb-4">
                    <h1 class="display-5 fw-bold">
                        <span class="text-white">🪖</span> SIS - COE
                    </h1>
                    <p class="text-muted">Sistema Institucional</p>
                </div>

                <div class="login-card">
                    <div class="card-header">
                        <h4 class="mb-0">Iniciar Sesión</h4>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-4">
                                <label class="form-label">Correo electrónico</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}" required autofocus placeholder="ejemplo@siscoe.bo">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Contraseña</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                       required placeholder="••••••••">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
                                Iniciar Sesión
                            </button>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-4 text-muted small">
                    © {{ date('Y') }} SIS - COE | Sistema Institucional
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
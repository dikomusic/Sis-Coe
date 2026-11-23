<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Acceso al Sistema' }} - SISCOE COE</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        coe: {
                            DEFAULT: '#FF0066',
                            magenta: '#FF0066',
                            hover: '#E6005C',
                            dark: '#18181B',
                            darker: '#121214',
                            gold: '#EDD00A',
                        }
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
</head>
<body class="h-full flex items-center justify-center p-4 bg-gradient-to-br from-[#121214] via-[#1E1E24] to-[#121214] text-slate-100">

    <div class="w-full max-w-md">
        @yield('content')
    </div>

</body>
</html>

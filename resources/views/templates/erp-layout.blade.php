<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-50 dark:bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'ERP Institucional' }} - SISCOE</title>

    <!-- Tailwind CSS CDN -->
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
                            pink: '#FF0066',
                            hover: '#E6005C',
                            dark: '#18181B',
                            darker: '#121214',
                            surface: '#242426',
                            gold: '#EDD00A',
                            goldDark: '#CAAC08',
                            light: '#FFF0F5',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        
        @media print {
            aside, header, .no-print {
                display: none !important;
            }
            body, main {
                background: white !important;
                padding: 0 !important;
                margin: 0 !important;
            }
        }
    </style>
</head>
<body class="h-full font-sans antialiased text-slate-800 dark:text-slate-100 flex" x-data="{ sidebarOpen: false }">

    <!-- Mobile Backdrop Overlay (Atomic Design: Global Layout) -->
    <div 
        x-show="sidebarOpen" 
        @click="sidebarOpen = false" 
        x-cloak 
        class="fixed inset-0 z-40 bg-slate-950/70 backdrop-blur-sm lg:hidden transition-opacity"
    ></div>

    <!-- Organismo: Sidebar ERP -->
    @include('organisms.erp-sidebar')

    <!-- Main Content Area (Multipantalla: pl-0 en móvil/tablet, lg:pl-64 en desktop) -->
    <div class="flex-1 flex flex-col pl-0 lg:pl-64 min-h-screen w-full min-w-0 transition-all duration-200">
        <!-- Organismo: Topbar ERP -->
        @include('organisms.erp-topbar', ['title' => $title ?? 'SISCOE ERP'])

        <!-- Alertas Flash -->
        <div class="px-6 pt-4">
            @if(session('success'))
                @include('atoms.alert', ['type' => 'success', 'title' => 'Operación Exitosa', 'slot' => session('success')])
            @endif

            @if(session('error'))
                @include('atoms.alert', ['type' => 'error', 'title' => 'Error', 'slot' => session('error')])
            @endif

            @if(isset($errors) && $errors->any())
                <div class="mb-3">
                    @include('atoms.alert', [
                        'type' => 'error', 
                        'title' => 'Por favor verifique los siguientes errores:', 
                        'slot' => '<ul class="list-disc pl-4 mt-1 space-y-0.5">' . implode('', array_map(fn($e) => '<li>'.$e.'</li>', $errors->all())) . '</ul>'
                    ])
                </div>
            @endif
        </div>

        <!-- Dynamic Page Content -->
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>

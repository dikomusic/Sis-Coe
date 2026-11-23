<aside 
    class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 border-r border-slate-800 flex flex-col justify-between transition-transform duration-200 ease-in-out lg:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
>
    <div>
        <!-- Brand Header con botón de cierre móvil -->
        <div class="h-16 flex items-center justify-between px-5 border-b border-slate-800 bg-slate-950/60">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo-coe.jpg') }}" alt="Logo COE" class="h-10 w-10 rounded-xl object-cover shadow-lg border border-[#FF0066]/60 flex-shrink-0">
                <div class="truncate">
                    <span class="block text-sm font-black tracking-wider text-white">SISCOE <span class="text-[#FF0066] font-bold">ERP</span></span>
                    <span class="block text-[10px] font-bold tracking-wider uppercase text-[#EDD00A] truncate">
                        {{ auth()->check() && auth()->user()->isCajero() && !auth()->user()->isAdmin() ? 'Ventanilla Cajas' : 'Círculo de Oficiales' }}
                    </span>
                </div>
            </div>

            <!-- Botón Cerrar en Dispositivos Móviles (Atomic Design: Atom) -->
            <button 
                type="button" 
                @click="sidebarOpen = false" 
                class="lg:hidden p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 focus:outline-none"
                aria-label="Cerrar Menú Lateral"
            >
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Navigation Links -->
        <nav class="p-4 space-y-1 overflow-y-auto max-h-[calc(100vh-140px)]">
            @if(auth()->check() && auth()->user()->isAdmin())
                <!-- ================= VISTA ADMINISTRADOR (ESTRUCTURA ORIGINAL) ================= -->
                <!-- DASHBOARD -->
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('admin.dashboard') || request()->routeIs('dashboard') ? 'bg-[#FF0066] text-white shadow-md shadow-[#FF0066]/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Dashboard</span>
                </a>

                <!-- MÓDULO SOCIOS -->
                <div class="pt-3 pb-1">
                    <span class="px-3.5 text-[10px] font-bold uppercase tracking-widest text-slate-500">Membresías</span>
                </div>

                <a href="{{ route('admin.socio.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('admin.socio.*') ? 'bg-[#FF0066] text-white shadow-md shadow-[#FF0066]/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span>Socios Titulares</span>
                </a>

                <a href="{{ route('beneficiarios.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('beneficiarios.*') ? 'bg-[#FF0066] text-white shadow-md shadow-[#FF0066]/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>Beneficiarios</span>
                </a>

                <a href="{{ route('cajas.alumnos') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('cajas.alumnos*') ? 'bg-[#FF0066] text-white shadow-md shadow-[#FF0066]/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span>Padrón de Alumnos</span>
                </a>

                <!-- FINANZAS Y CAJAS (ORIGINAL: ACCESO GENERAL AL MÓDULO) -->
                <div class="pt-3 pb-1">
                    <span class="px-3.5 text-[10px] font-bold uppercase tracking-widest text-slate-500">Finanzas y Cajas</span>
                </div>

                <a href="{{ route('cajas.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('cajas.*') ? 'bg-[#FF0066] text-white shadow-md shadow-[#FF0066]/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>Módulo Cajas</span>
                </a>

                <!-- MÓDULO CONSULTAS -->
                <div class="pt-3 pb-1">
                    <span class="px-3.5 text-[10px] font-bold uppercase tracking-widest text-slate-500">Operaciones</span>
                </div>

                <a href="{{ route('consultas.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('consultas.*') ? 'bg-[#FF0066] text-white shadow-md shadow-[#FF0066]/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <span>Búsqueda y Consultas</span>
                </a>

                <!-- MÓDULO USUARIOS Y SEGURIDAD -->
                <div class="pt-3 pb-1">
                    <span class="px-3.5 text-[10px] font-bold uppercase tracking-widest text-slate-500">Sistema</span>
                </div>

                <a href="{{ route('usuarios.create', ['rol' => 'USUARIO']) }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('usuarios.*') ? 'bg-[#FF0066] text-white shadow-md shadow-[#FF0066]/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span>Gestión Usuarios</span>
                </a>

            @elseif(auth()->check() && auth()->user()->isCajero())
                <!-- ================= VISTA CAJERO (PORTAL DEDICADO DE CAJAS) ================= -->
                <div class="pt-1 pb-1">
                    <span class="px-3.5 text-[10px] font-bold uppercase tracking-widest text-slate-500">Ventanilla & Operaciones</span>
                </div>

                <a href="{{ route('cajas.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('cajas.index') ? 'bg-[#FF0066] text-white shadow-md shadow-[#FF0066]/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Panel de Caja</span>
                </a>

                <a href="{{ route('cajas.ventanilla') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('cajas.ventanilla') || request()->routeIs('cajas.create') ? 'bg-[#FF0066] text-white shadow-md shadow-[#FF0066]/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <div class="flex items-center justify-between w-full">
                        <span>Ventanilla Cobro</span>
                        <span class="px-1.5 py-0.2 text-[9px] font-black rounded bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">Rápido</span>
                    </div>
                </a>

                <a href="{{ route('cajas.historial') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('cajas.historial') || request()->routeIs('cajas.tarjeta') ? 'bg-[#FF0066] text-white shadow-md shadow-[#FF0066]/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    <span>Historial & Tarjetas</span>
                </a>

                <a href="{{ route('cajas.alumnos') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('cajas.alumnos*') ? 'bg-[#FF0066] text-white shadow-md shadow-[#FF0066]/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <div class="flex items-center justify-between w-full">
                        <span>Padrón de Alumnos</span>
                        <span class="px-1.5 py-0.2 text-[9px] font-black rounded bg-[#FF0066]/20 text-[#FF0066] border border-[#FF0066]/30">Únicos</span>
                    </div>
                </a>

                <a href="{{ route('cajas.horarios') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('cajas.horarios') ? 'bg-[#FF0066] text-white shadow-md shadow-[#FF0066]/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Horarios y Cupos</span>
                </a>

                <a href="{{ route('cajas.reprogramaciones') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('cajas.reprogramaciones') ? 'bg-[#FF0066] text-white shadow-md shadow-[#FF0066]/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>Reprogramaciones</span>
                </a>

                <a href="{{ route('cajas.reportes') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('cajas.reportes') ? 'bg-[#FF0066] text-white shadow-md shadow-[#FF0066]/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span>Arqueo y Reportes</span>
                </a>

            @else
                <!-- ================= OTROS ROLES (USUARIO / INVITADO) ================= -->
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('dashboard') ? 'bg-[#FF0066] text-white shadow-md shadow-[#FF0066]/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Dashboard</span>
                </a>

                <div class="pt-3 pb-1">
                    <span class="px-3.5 text-[10px] font-bold uppercase tracking-widest text-slate-500">Operaciones</span>
                </div>

                <a href="{{ route('consultas.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('consultas.*') ? 'bg-[#FF0066] text-white shadow-md shadow-[#FF0066]/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <span>Búsqueda y Consultas</span>
                </a>
            @endif
        </nav>
    </div>

    <!-- Bottom Footer Profile -->
    <div class="p-4 border-t border-slate-800 bg-slate-950/30 flex items-center justify-between">
        <div class="flex items-center gap-3 truncate">
            <div class="h-9 w-9 rounded-xl bg-gradient-to-tr from-[#FF0066] to-[#C91459] text-white font-bold flex items-center justify-center text-xs shadow-md shadow-[#FF0066]/20 flex-shrink-0">
                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
            </div>
            <div class="truncate">
                <span class="block text-xs font-bold text-white truncate">{{ auth()->user()->name ?? 'Usuario' }}</span>
                <span class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider">{{ auth()->user()->rol ?? 'INVITADO' }}</span>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-500 rounded-lg hover:bg-slate-800 transition-colors" title="Cerrar Sesión">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
            </button>
        </form>
    </div>
</aside>
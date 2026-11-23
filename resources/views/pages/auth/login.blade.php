@extends('templates.auth-layout', ['title' => 'Iniciar Sesión'])

@section('content')
<div class="rounded-3xl border border-slate-800 bg-slate-900/80 backdrop-blur-xl p-8 shadow-2xl space-y-6">
    <div class="text-center space-y-2">
        <div class="inline-flex items-center justify-center">
            <img src="{{ asset('images/logo-coe.jpg') }}" alt="Logo COE" class="h-20 w-20 rounded-2xl object-cover shadow-xl border-2 border-[#FF0066]/70">
        </div>
        <h2 class="text-2xl font-black tracking-tight text-white">SISCOE <span class="text-[#FF0066]">ERP</span></h2>
        <p class="text-xs font-bold text-[#EDD00A]">Círculo de Oficiales del Ejército - La Paz, Bolivia</p>
    </div>

    @if(isset($errors) && $errors->any())
        <div class="p-3.5 rounded-xl bg-rose-950/60 border border-rose-800 text-rose-300 text-xs text-center font-medium">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Correo Electrónico Institucional</label>
            <input 
                type="email" 
                name="email" 
                value="{{ old('email') }}" 
                required 
                autofocus 
                placeholder="ejemplo@siscoe.bo" 
                class="block w-full rounded-xl border border-slate-700 bg-slate-950/60 px-4 py-3 text-sm text-white placeholder-slate-500 focus:border-[#FF0066] focus:ring-2 focus:ring-[#FF0066]/20 transition-all"
            >
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Contraseña de Acceso</label>
            <input 
                type="password" 
                name="password" 
                required 
                placeholder="••••••••" 
                class="block w-full rounded-xl border border-slate-700 bg-slate-950/60 px-4 py-3 text-sm text-white placeholder-slate-500 focus:border-[#FF0066] focus:ring-2 focus:ring-[#FF0066]/20 transition-all"
            >
        </div>

        <button 
            type="submit" 
            class="w-full py-3.5 px-4 rounded-xl bg-[#FF0066] hover:bg-[#E6005C] text-white font-black text-sm shadow-lg shadow-[#FF0066]/30 transition-all active:scale-[0.99] flex items-center justify-center gap-2 cursor-pointer"
        >
            <span>Ingresar al Sistema</span>
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
        </button>
    </form>

    <div class="border-t border-slate-800/80 pt-4 text-center text-[11px] text-slate-500">
        Sistema Institucional COE • Acceso restringido a personal autorizado
    </div>
</div>
@endsection

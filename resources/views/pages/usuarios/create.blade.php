@extends('templates.erp-layout', ['title' => 'Crear Usuario'])

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="border-b border-slate-200 dark:border-slate-800 pb-4">
        <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Crear Nuevo Usuario del Sistema</h2>
        <p class="text-xs text-slate-500 mt-0.5">Asignación de roles y credenciales de acceso al SISCOE ERP.</p>
    </div>

    <form method="POST" action="{{ route('usuarios.store') }}" class="space-y-6">
        @csrf

        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm space-y-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Nombre Completo *</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Ej. Juan Pérez Ramos" class="block w-full rounded-xl border-slate-300 sm:text-xs py-2.5 px-3 focus:border-blue-600">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Correo Electrónico *</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="usuario@siscoe.bo" class="block w-full rounded-xl border-slate-300 sm:text-xs py-2.5 px-3 focus:border-blue-600">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Rol en el Sistema *</label>
                <select name="rol" class="block w-full rounded-xl border-slate-300 sm:text-xs py-2.5 px-3 focus:border-blue-600" required>
                    <option value="ADMIN" @selected(old('rol', $rol) === 'ADMIN')>ADMIN - Acceso Total al ERP</option>
                    <option value="CAJERO" @selected(old('rol', $rol) === 'CAJERO')>CAJERO - Ventanilla de Cobros e Inscripciones</option>
                    <option value="USUARIO" @selected(old('rol', $rol) === 'USUARIO')>USUARIO - Oficial / Socio</option>
                    <option value="INVITADO" @selected(old('rol', $rol) === 'INVITADO')>INVITADO - Portería / Consultas</option>
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Contraseña *</label>
                    <input type="password" name="password" required placeholder="Mínimo 6 caracteres" class="block w-full rounded-xl border-slate-300 sm:text-xs py-2.5 px-3 focus:border-blue-600">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Confirmar Contraseña *</label>
                    <input type="password" name="password_confirmation" required placeholder="Repetir contraseña" class="block w-full rounded-xl border-slate-300 sm:text-xs py-2.5 px-3 focus:border-blue-600">
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('dashboard') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold border border-slate-300 hover:bg-slate-100 transition-colors">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-600/25 transition-all">
                Registrar Usuario
            </button>
        </div>
    </form>
</div>
@endsection

<?php

use Illuminate\Support\Facades\Route;

// ========================================================
// CONTROLADORES MODULARES (SCREAMING ARCHITECTURE)
// ========================================================
use App\Modules\Seguridad\Http\Controllers\LoginController;
use App\Modules\Seguridad\Http\Controllers\DashboardController;
use App\Modules\Seguridad\Http\Controllers\UsuarioController;
use App\Modules\Socios\Http\Controllers\SocioTitularController;
use App\Modules\Socios\Http\Controllers\BeneficiarioTitularController;
use App\Modules\Socios\Http\Controllers\DocumentoRespaldoController;
use App\Modules\Credenciales\Http\Controllers\CredencialController;
use App\Modules\Consultas\Http\Controllers\ConsultaGeneralController;
use App\Modules\Consultas\Http\Controllers\ConsultaBeneficiarioController;
use App\Modules\Consultas\Http\Controllers\ConsultaCredencialController;
use App\Modules\Cajas\Http\Controllers\CajaController as CajasModuleController;

// ========================================================
// RUTAS PÚBLICAS Y AUTENTICACIÓN
// ========================================================
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ========================================================
// REDIRECCIÓN INTELIGENTE DE DASHBOARD
// ========================================================
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->isAdmin()) return redirect()->route('admin.dashboard');
        if ($user->isCajero()) return redirect()->route('cajas.index');
        if ($user->isUsuario()) return redirect()->route('usuario.dashboard');
        if ($user->isInvitado()) return redirect()->route('invitado.dashboard');
        return redirect()->route('consultas.index');
    })->name('dashboard');

    Route::get('/home', function () {
        return redirect()->route('dashboard');
    })->name('home');

    // ====================================================
    // MÓDULO SEGURIDAD Y DASHBOARDS POR ROL
    // ====================================================
    Route::middleware('role:ADMIN')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

        // Recursos de socios dentro de admin
        Route::resource('socio', SocioTitularController::class);
        Route::get('socio/{id_titular}/beneficiarios', [BeneficiarioTitularController::class, 'index'])->name('socio.beneficiario.index');
        Route::get('socio/{id_titular}/beneficiarios/create', [BeneficiarioTitularController::class, 'create'])->name('socio.beneficiario.create');
        Route::post('socio/{id_titular}/beneficiarios', [BeneficiarioTitularController::class, 'store'])->name('socio.beneficiario.store');
        Route::get('socio/{id_titular}/beneficiarios/{id}/edit', [BeneficiarioTitularController::class, 'edit'])->name('socio.beneficiario.edit');
        Route::put('socio/{id_titular}/beneficiarios/{id}', [BeneficiarioTitularController::class, 'update'])->name('socio.beneficiario.update');
        Route::delete('socio/{id_titular}/beneficiarios/{id}', [BeneficiarioTitularController::class, 'destroy'])->name('socio.beneficiario.destroy');
        Route::get('/socio/{id}/ficha', [SocioTitularController::class, 'ficha'])->name('socio.ficha');
        Route::get('/socio/{id}/ficha/pdf', [SocioTitularController::class, 'fichaPdf'])->name('socio.ficha.pdf');
    });

    Route::middleware('role:USUARIO')->prefix('usuario')->name('usuario.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'usuario'])->name('dashboard');
    });

    Route::middleware('role:INVITADO')->prefix('invitado')->name('invitado.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'invitado'])->name('dashboard');
    });

    Route::middleware('role:ADMIN')->group(function () {
        Route::get('/usuarios/crear/{rol}', [UsuarioController::class, 'create'])->name('usuarios.create');
        Route::post('/usuarios/guardar', [UsuarioController::class, 'store'])->name('usuarios.store');
    });

    // ====================================================
    // MÓDULO SOCIOS & BENEFICIARIOS
    // ====================================================
    Route::resource('beneficiarios', BeneficiarioTitularController::class)->middleware('role:ADMIN,USUARIO');

    // Documentos de Respaldo
    Route::get('/documentos/create/{tipo}/{id}', [DocumentoRespaldoController::class, 'create'])->name('documentos.create');
    Route::post('/documentos/store/{tipo}/{id}', [DocumentoRespaldoController::class, 'store'])->name('documentos.store');
    Route::get('/documentos/edit/{documento}', [DocumentoRespaldoController::class, 'edit'])->name('documentos.edit');
    Route::put('/documentos/update/{documento}', [DocumentoRespaldoController::class, 'update'])->name('documentos.update');

    // ====================================================
    // MÓDULO CREDENCIALES
    // ====================================================
    Route::get('/credenciales/create/{tipo}/{id}', [CredencialController::class, 'create'])->name('credenciales.create');
    Route::post('/credenciales/store/{tipo}/{id}', [CredencialController::class, 'store'])->name('credenciales.store');
    Route::get('/credenciales/edit/{tipo}/{id}/{credencial}', [CredencialController::class, 'edit'])->name('credenciales.edit');
    Route::put('/credenciales/update/{tipo}/{id}/{credencial}', [CredencialController::class, 'update'])->name('credenciales.update');
    Route::get('/credenciales/imprimir/{tipo}/{id}', [CredencialController::class, 'imprimir'])->name('credenciales.imprimir');

    // ====================================================
    // MÓDULO CONSULTAS & VERIFICACIÓN
    // ====================================================
    Route::middleware('role:ADMIN,INVITADO,USUARIO')->prefix('consultas')->name('consultas.')->group(function () {
        Route::get('/', [ConsultaGeneralController::class, 'index'])->name('index');
        Route::get('/search', [ConsultaGeneralController::class, 'search'])->name('search');
        Route::get('/detalle/{id}/{tipo}', [ConsultaGeneralController::class, 'detalle'])->name('detalle');
        Route::get('/socios', [ConsultaGeneralController::class, 'buscar'])->name('socio.buscar');
        Route::get('/socios/{id}', [ConsultaGeneralController::class, 'show'])->name('socio.show');
        Route::get('/beneficiarios', [ConsultaBeneficiarioController::class, 'buscar'])->name('beneficiario.buscar');
        Route::get('/beneficiarios/{id}', [ConsultaBeneficiarioController::class, 'show'])->name('beneficiario.show');
        Route::get('/buscar-credencial', [ConsultaCredencialController::class, 'buscar'])->name('credencial.buscar');
    });

    // ====================================================
    // MÓDULO CAJAS & FINANZAS
    // ====================================================
    Route::middleware('role:ADMIN,CAJERO,CAJAS')->prefix('cajas')->name('cajas.')->group(function () {
        Route::get('/', [CajasModuleController::class, 'index'])->name('index');
        Route::get('/ventanilla', [CajasModuleController::class, 'ventanilla'])->name('ventanilla');
        Route::get('/nueva-inscripcion', [CajasModuleController::class, 'ventanilla'])->name('create');
        Route::post('/inscripciones', [CajasModuleController::class, 'guardarInscripcion'])->name('store');
        Route::post('/inscripciones-lote', [CajasModuleController::class, 'guardarLoteInscripciones'])->name('store-lote');
        Route::get('/historial', [CajasModuleController::class, 'historial'])->name('historial');
        Route::get('/alumnos', [CajasModuleController::class, 'alumnos'])->name('alumnos');
        Route::get('/alumnos/{id}', [CajasModuleController::class, 'detalleAlumno'])->name('alumnos.detalle');
        Route::get('/tarjeta/{id}', [CajasModuleController::class, 'verTarjeta'])->name('tarjeta');
        Route::get('/horarios-cupos', [CajasModuleController::class, 'horarios'])->name('horarios');
        Route::get('/reprogramaciones', [CajasModuleController::class, 'reprogramaciones'])->name('reprogramaciones');
        Route::post('/reprogramaciones', [CajasModuleController::class, 'guardarReprogramacion'])->name('reprogramaciones.store');
        Route::get('/reportes', [CajasModuleController::class, 'reportes'])->name('reportes');

        // APIs AJAX para la ventanilla rápida
        Route::get('/api/buscar-persona', [CajasModuleController::class, 'buscarPersona'])->name('api.buscarPersona');
        Route::get('/api/disciplinas/{id}/horarios', [CajasModuleController::class, 'obtenerHorariosPorDisciplina'])->name('api.horariosPorDisciplina');
        Route::post('/api/registrar-particular', [CajasModuleController::class, 'registrarParticular'])->name('api.registrarParticular');
    });
});

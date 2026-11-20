<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Usuario\DashboardController as UsuarioDashboard;
use App\Http\Controllers\Invitado\DashboardController as InvitadoDashboard;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ConsultaSocioController;
use App\Http\Controllers\ConsultaBeneficiarioController;
use App\Http\Controllers\ConsultaCredencialController;
use App\Http\Controllers\ConsultaGeneralController;
use App\Http\Controllers\SocioTitularController;
use App\Http\Controllers\BeneficiarioTitularController;
use App\Http\Controllers\DocumentoRespaldoController;
use App\Http\Controllers\CredencialController;

// Rutas públicas (sin autenticación)
Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->get('/dashboard', function () {
    $user = auth()->user();
    if ($user->isAdmin()) return redirect()->route('admin.dashboard');
    if ($user->isUsuario()) return redirect()->route('usuario.dashboard');
    if ($user->isInvitado()) return redirect()->route('invitado.dashboard');
    return redirect('/home');
})->name('dashboard');

// Rutas protegidas por autenticación (todas requieren estar logueado)
Route::middleware('auth')->group(function () {

    // Redirección inteligente después del login
    Route::get('/home', function () {
        $user = auth()->user();
        if ($user->isAdmin()) return redirect()->route('admin.dashboard');
        if ($user->isUsuario()) return redirect()->route('usuario.dashboard');
        if ($user->isInvitado()) return redirect()->route('invitado.dashboard');
        return redirect('/');
    })->name('home');

    // Área Administrador (solo rol ADMIN)
    Route::middleware('role:ADMIN')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    
    Route::resource('socio', SocioTitularController::class);
    Route::get('socio/{id_titular}/beneficiarios', 
    [BeneficiarioTitularController::class, 'index'])->name('socio.beneficiario.index');
    Route::get('socio/{id_titular}/beneficiarios/create', 
    [BeneficiarioTitularController::class, 'create'])->name('socio.beneficiario.create');
    Route::post('socio/{id_titular}/beneficiarios', 
    [BeneficiarioTitularController::class, 'store'])->name('socio.beneficiario.store');

    Route::get('socio/{id_titular}/beneficiarios/{id}/edit', 
    [BeneficiarioTitularController::class, 'edit'])->name('socio.beneficiario.edit');

    Route::put('socio/{id_titular}/beneficiarios/{id}', 
    [BeneficiarioTitularController::class, 'update'])->name('socio.beneficiario.update');

    Route::delete('socio/{id_titular}/beneficiarios/{id}', 
    [BeneficiarioTitularController::class, 'destroy'])->name('socio.beneficiario.destroy');
        // Otras rutas de administración aquí...
    });

    // Área Usuario / Socio (solo rol USUARIO)
    Route::middleware('role:USUARIO')->prefix('usuario')->name('usuario.')->group(function () {
        Route::get('/dashboard', [UsuarioDashboard::class, 'index'])->name('dashboard');
        // Otras rutas de socios aquí...
    });

    // Área Invitado (solo rol INVITADO)
    Route::middleware('role:INVITADO')->prefix('invitado')->name('invitado.')->group(function () {
        Route::get('/dashboard', [InvitadoDashboard::class, 'index'])->name('dashboard');
        // Otras rutas básicas para invitados aquí...
    });

    // Gestión de usuarios (solo ADMIN)
    Route::middleware('role:ADMIN')->group(function () {
        Route::get('/usuarios/crear/{rol}', [UsuarioController::class, 'create'])->name('usuarios.create');
        Route::post('/usuarios/guardar', [UsuarioController::class, 'store'])->name('usuarios.store');
    });

    // Recursos completos (CRUD) de socios y beneficiarios (solo ADMIN o USUARIO según necesites)

    Route::resource('beneficiarios', BeneficiarioTitularController::class)->middleware('role:ADMIN|USUARIO');

    // Documentos y credenciales (CRUD)
    Route::get('/documentos/create/{tipo}/{id}', [DocumentoRespaldoController::class, 'create'])->name('documentos.create');
    Route::post('/documentos/store/{tipo}/{id}', [DocumentoRespaldoController::class, 'store'])->name('documentos.store');
    Route::get('/documentos/edit/{documento}', [DocumentoRespaldoController::class, 'edit'])->name('documentos.edit');
    Route::put('/documentos/update/{documento}', [DocumentoRespaldoController::class, 'update'])->name('documentos.update');

    Route::get('/credenciales/create/{tipo}/{id}', [CredencialController::class, 'create'])->name('credenciales.create');
    Route::post('/credenciales/store/{tipo}/{id}', [CredencialController::class, 'store'])->name('credenciales.store');
    Route::get('/credenciales/edit/{tipo}/{id}/{credencial}', [CredencialController::class, 'edit'])->name('credenciales.edit');
    Route::put('/credenciales/update/{tipo}/{id}/{credencial}', [CredencialController::class, 'update'])->name('credenciales.update');
    Route::get('/credenciales/imprimir/{tipo}/{id}', [CredencialController::class, 'imprimir'])->name('credenciales.imprimir');

    // MÓDULO DE CONSULTAS (SOLO PARA INVITADOS)
    Route::middleware('role:INVITADO')->prefix('consultas')->name('consultas.')->group(function () {
        // Página principal de consultas (con formulario)
        Route::get('/', [ConsultaGeneralController::class, 'index'])->name('general');

        // Búsqueda general (tablas de resultados)
        Route::get('/search', [ConsultaGeneralController::class, 'search'])->name('search');

        // Detalle de socio o beneficiario (abre en ventana nueva)
        Route::get('/detalle/{id}/{tipo}', [ConsultaGeneralController::class, 'detalle'])->name('detalle');

        // Consultas específicas (opcionales, si las quieres mantener)
        Route::get('/socios', [ConsultaSocioController::class, 'buscar'])->name('socio.buscar');
        Route::get('/socios/{id}', [ConsultaSocioController::class, 'show'])->name('socio.show');

        Route::get('/beneficiarios', [ConsultaBeneficiarioController::class, 'buscar'])->name('beneficiario.buscar');
        Route::get('/beneficiarios/{id}', [ConsultaBeneficiarioController::class, 'show'])->name('beneficiario.show');

        // Búsqueda por credencial
        Route::get('/buscar-credencial', [ConsultaCredencialController::class, 'buscar'])->name('credencial.buscar');
    });
}); 
<?php

namespace App\Modules\Cajas\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Modules\Cajas\Contracts\PersonaRepositoryInterface;
use App\Modules\Cajas\Contracts\InscripcionRepositoryInterface;
use App\Modules\Cajas\Contracts\HorarioCursoRepositoryInterface;
use App\Modules\Cajas\Repositories\EloquentPersonaRepository;
use App\Modules\Cajas\Repositories\EloquentInscripcionRepository;
use App\Modules\Cajas\Repositories\EloquentHorarioCursoRepository;

class CajasModuleServiceProvider extends ServiceProvider
{
    /**
     * Todos los bindings de contratos a implementaciones del módulo Cajas
     */
    public array $bindings = [
        PersonaRepositoryInterface::class => EloquentPersonaRepository::class,
        InscripcionRepositoryInterface::class => EloquentInscripcionRepository::class,
        HorarioCursoRepositoryInterface::class => EloquentHorarioCursoRepository::class,
    ];

    public function register(): void
    {
        // Bindings declarados arriba
    }

    public function boot(): void
    {
        // Registrar componentes anónimos de Atomic Design para cajas
        if (is_dir(resource_path('views/cajas'))) {
            Blade::anonymousComponentPath(resource_path('views/cajas'), 'cajas');
        }
    }
}

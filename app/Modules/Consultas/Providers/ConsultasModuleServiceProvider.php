<?php

namespace App\Modules\Consultas\Providers;

use Illuminate\Support\ServiceProvider;
use App\Modules\Consultas\Contracts\BusquedaGeneralRepositoryInterface;
use App\Modules\Consultas\Repositories\EloquentBusquedaGeneralRepository;

class ConsultasModuleServiceProvider extends ServiceProvider
{
    public array $bindings = [
        BusquedaGeneralRepositoryInterface::class => EloquentBusquedaGeneralRepository::class,
    ];

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        //
    }
}

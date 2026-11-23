<?php

namespace App\Modules\Seguridad\Providers;

use Illuminate\Support\ServiceProvider;
use App\Modules\Seguridad\Contracts\UsuarioRepositoryInterface;
use App\Modules\Seguridad\Repositories\EloquentUsuarioRepository;

class SeguridadModuleServiceProvider extends ServiceProvider
{
    public array $bindings = [
        UsuarioRepositoryInterface::class => EloquentUsuarioRepository::class,
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

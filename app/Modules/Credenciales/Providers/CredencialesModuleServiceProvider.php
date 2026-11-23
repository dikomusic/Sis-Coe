<?php

namespace App\Modules\Credenciales\Providers;

use Illuminate\Support\ServiceProvider;
use App\Modules\Credenciales\Contracts\CredencialRepositoryInterface;
use App\Modules\Credenciales\Repositories\EloquentCredencialRepository;

class CredencialesModuleServiceProvider extends ServiceProvider
{
    public array $bindings = [
        CredencialRepositoryInterface::class => EloquentCredencialRepository::class,
    ];

    public function register(): void
    {
        // Bindings declarados arriba
    }

    public function boot(): void
    {
        //
    }
}

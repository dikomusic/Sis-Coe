<?php

namespace App\Modules\Socios\Providers;

use Illuminate\Support\ServiceProvider;
use App\Modules\Socios\Contracts\SocioRepositoryInterface;
use App\Modules\Socios\Contracts\BeneficiarioRepositoryInterface;
use App\Modules\Socios\Contracts\DocumentoRepositoryInterface;
use App\Modules\Socios\Repositories\EloquentSocioRepository;
use App\Modules\Socios\Repositories\EloquentBeneficiarioRepository;
use App\Modules\Socios\Repositories\EloquentDocumentoRepository;

class SociosModuleServiceProvider extends ServiceProvider
{
    public array $bindings = [
        SocioRepositoryInterface::class => EloquentSocioRepository::class,
        BeneficiarioRepositoryInterface::class => EloquentBeneficiarioRepository::class,
        DocumentoRepositoryInterface::class => EloquentDocumentoRepository::class,
    ];

    public function register(): void
    {
        // Bindings declarados arriba
    }

    public function boot(): void
    {
        // Bootstrapping del módulo si aplica
    }
}

<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Modules\Cajas\Providers\CajasModuleServiceProvider::class,
    App\Modules\Socios\Providers\SociosModuleServiceProvider::class,
    App\Modules\Credenciales\Providers\CredencialesModuleServiceProvider::class,
    App\Modules\Consultas\Providers\ConsultasModuleServiceProvider::class,
    App\Modules\Seguridad\Providers\SeguridadModuleServiceProvider::class,
];

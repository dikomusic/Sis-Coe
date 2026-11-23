<?php

namespace App\Modules\Consultas\Services;

use App\Modules\Consultas\Contracts\BusquedaGeneralRepositoryInterface;

class BusquedaGeneralService
{
    public function __construct(
        protected BusquedaGeneralRepositoryInterface $busquedaRepo
    ) {}

    public function verificarCredencial(int|string $codigo): array
    {
        $credencial = $this->busquedaRepo->buscarCredencialPorId($codigo);

        $entidad = null;
        $mensajeError = null;

        if ($credencial) {
            $entidad = $credencial->entidad;
            if ($credencial->estado !== 'activo') {
                $mensajeError = 'La credencial está INACTIVA. Motivo: ' . ($credencial->estado_credencial ?? 'No especificado');
            } elseif ($credencial->fecha_vencimiento && now()->gt($credencial->fecha_vencimiento)) {
                $mensajeError = 'La credencial se encuentra VENCIDA desde ' . $credencial->fecha_vencimiento->format('d/m/Y');
            }
        } else {
            $mensajeError = 'Credencial INEXISTENTE o no encontrada con el código ingresado.';
        }

        return [
            'credencial'   => $credencial,
            'entidad'      => $entidad,
            'mensajeError' => $mensajeError,
        ];
    }
}

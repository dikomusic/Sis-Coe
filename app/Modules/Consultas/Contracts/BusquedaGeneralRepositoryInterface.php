<?php

namespace App\Modules\Consultas\Contracts;

use App\Models\Credencial;
use Illuminate\Pagination\LengthAwarePaginator;

interface BusquedaGeneralRepositoryInterface
{
    public function buscarCredencialPorId(int|string $codigo): ?Credencial;
    public function buscarSociosPorTexto(string $texto, int $porPagina = 15): LengthAwarePaginator;
    public function buscarBeneficiariosPorTexto(string $texto, int $porPagina = 15): LengthAwarePaginator;
    public function obtenerDetalleEntidad(string $tipo, int $id): array;
}

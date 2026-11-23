<?php

namespace App\Modules\Socios\Contracts;

use App\Shared\Contracts\BaseRepositoryInterface;
use App\Models\SocioTitular;
use Illuminate\Pagination\LengthAwarePaginator;

interface SocioRepositoryInterface extends BaseRepositoryInterface
{
    public function listarConFiltros(array $filtros, int $porPagina = 15): LengthAwarePaginator;
    public function buscarPorId(int $id): ?SocioTitular;
    public function crear(array $datos): SocioTitular;
    public function actualizar(int $id, array $datos): SocioTitular;
    public function eliminar(int $id): bool;
}

<?php

namespace App\Modules\Socios\Contracts;

use App\Shared\Contracts\BaseRepositoryInterface;
use App\Models\BeneficiarioTitular;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface BeneficiarioRepositoryInterface extends BaseRepositoryInterface
{
    public function listarPorTitular(int $titularId): Collection;
    public function listarTodosPaginados(array $filtros, int $porPagina = 15): LengthAwarePaginator;
    public function buscarPorId(int $id): ?BeneficiarioTitular;
    public function crear(array $datos): BeneficiarioTitular;
    public function actualizar(int $id, array $datos): BeneficiarioTitular;
    public function eliminar(int $id): bool;
}

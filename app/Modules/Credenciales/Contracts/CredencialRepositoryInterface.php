<?php

namespace App\Modules\Credenciales\Contracts;

use App\Shared\Contracts\BaseRepositoryInterface;
use App\Models\Credencial;
use Illuminate\Support\Collection;

interface CredencialRepositoryInterface extends BaseRepositoryInterface
{
    public function obtenerUltimaPorEntidad(string $entidadType, int $entidadId): ?Credencial;
    public function listarPorEntidad(string $entidadType, int $entidadId): Collection;
    public function buscarPorId(int $id): ?Credencial;
    public function crear(array $datos): Credencial;
    public function actualizar(int $id, array $datos): Credencial;
}

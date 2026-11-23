<?php

namespace App\Modules\Socios\Contracts;

use App\Models\DocumentoRespaldo;
use Illuminate\Support\Collection;

interface DocumentoRepositoryInterface
{
    public function listarPorEntidad(string $tipo, int $id): Collection;
    public function buscarPorId(int $id): ?DocumentoRespaldo;
    public function crear(array $datos): DocumentoRespaldo;
    public function actualizar(int $id, array $datos): DocumentoRespaldo;
    public function eliminar(int $id): bool;
}

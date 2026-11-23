<?php

namespace App\Modules\Cajas\Contracts;

use App\Shared\Contracts\BaseRepositoryInterface;
use App\Models\Inscripcion;
use App\Models\PagoFactura;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface InscripcionRepositoryInterface extends BaseRepositoryInterface
{
    public function create(array $datosInscripcion): Inscripcion;
    public function createPago(array $datosPago): PagoFactura;
    public function findByIdWithRelations(int $id): ?Inscripcion;
    public function getRecientes(int $limit = 8): Collection;
    public function paginateConFiltros(array $filtros, int $perPage = 15): LengthAwarePaginator;
    public function getSiguienteNumeroFactura(): string;
    public function getMetricasHoy(string $fecha): array;
    public function getIngresosPorPeriodo(string $fechaInicio, string $fechaFin): Collection;
}

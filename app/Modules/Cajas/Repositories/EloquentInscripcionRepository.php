<?php

namespace App\Modules\Cajas\Repositories;

use App\Shared\Repositories\BaseEloquentRepository;
use App\Models\Inscripcion;
use App\Models\PagoFactura;
use App\Modules\Cajas\Contracts\InscripcionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EloquentInscripcionRepository extends BaseEloquentRepository implements InscripcionRepositoryInterface
{
    protected function getModel(): Model
    {
        return new Inscripcion();
    }

    public function create(array $datosInscripcion): Inscripcion
    {
        return Inscripcion::create($datosInscripcion);
    }

    public function createPago(array $datosPago): PagoFactura
    {
        return PagoFactura::create($datosPago);
    }

    public function findByIdWithRelations(int $id): ?Inscripcion
    {
        return Inscripcion::with([
            'persona',
            'horarioCurso.disciplina',
            'horarioCurso.instructor.persona',
            'tarifa.modalidad',
            'pago',
            'tarjeta'
        ])->find($id);
    }

    public function getRecientes(int $limit = 8): Collection
    {
        return Inscripcion::with(['persona', 'horarioCurso.disciplina', 'pago', 'tarjeta'])
            ->latest()
            ->take($limit)
            ->get();
    }

    public function paginateConFiltros(array $filtros, int $perPage = 15): LengthAwarePaginator
    {
        $query = Inscripcion::with(['persona', 'horarioCurso.disciplina', 'pago', 'tarjeta']);

        if (!empty($filtros['buscar'])) {
            $b = $filtros['buscar'];
            $query->where(function($q) use ($b) {
                $q->where('codigo_inscripcion', 'ilike', "%{$b}%")
                  ->orWhereHas('persona', function($qp) use ($b) {
                      $qp->where('ci', 'ilike', "%{$b}%")
                         ->orWhere('nombres', 'ilike', "%{$b}%")
                         ->orWhere('paterno', 'ilike', "%{$b}%");
                  })
                  ->orWhereHas('pago', function($qp) use ($b) {
                      $qp->where('numero_factura', 'ilike', "%{$b}%");
                  });
            });
        }

        if (!empty($filtros['disciplina_id'])) {
            $query->whereHas('horarioCurso', fn($q) => $q->where('disciplina_id', $filtros['disciplina_id']));
        }

        if (!empty($filtros['estado'])) {
            $query->where('estado', $filtros['estado']);
        }

        return $query->latest()->paginate($perPage);
    }

    public function getSiguienteNumeroFactura(): string
    {
        $ultimoId = PagoFactura::max('id') ?? 0;
        $correlativo = str_pad($ultimoId + 1, 5, '0', STR_PAD_LEFT);
        return 'FAC-' . date('Y') . '-' . $correlativo;
    }

    public function getMetricasHoy(string $fecha): array
    {
        return [
            'total_inscritos' => Inscripcion::whereDate('created_at', $fecha)->count(),
            'total_recaudado' => PagoFactura::whereDate('created_at', $fecha)->sum('importe_final'),
            'reprogramaciones_mes' => DB::table('reprogramaciones')->whereMonth('created_at', date('m'))->count(),
            'cursos_activos' => DB::table('horarios_cursos')->where('activo', true)->count()
        ];
    }

    public function getIngresosPorPeriodo(string $fechaInicio, string $fechaFin): Collection
    {
        return PagoFactura::whereBetween(DB::raw('DATE(created_at)'), [$fechaInicio, $fechaFin])
            ->with(['inscripcion.horarioCurso.disciplina'])
            ->get();
    }
}

<?php

namespace App\Modules\Socios\Repositories;

use App\Shared\Repositories\BaseEloquentRepository;
use App\Modules\Socios\Contracts\SocioRepositoryInterface;
use App\Models\SocioTitular;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentSocioRepository extends BaseEloquentRepository implements SocioRepositoryInterface
{
    protected function getModel(): Model
    {
        return new SocioTitular();
    }

    public function listarConFiltros(array $filtros, int $porPagina = 15): LengthAwarePaginator
    {
        $query = SocioTitular::query();

        if (!empty($filtros['buscar'])) {
            $texto = trim($filtros['buscar']);
            $palabras = preg_split('/\s+/', $texto, 2);

            $query->where(function ($q) use ($texto, $palabras) {
                if (count($palabras) === 2) {
                    $q->where(function ($qq) use ($palabras) {
                        $qq->where('paterno', 'LIKE', "%{$palabras[0]}%")
                           ->where('materno', 'LIKE', "%{$palabras[1]}%");
                    })->orWhere(function ($qq) use ($palabras) {
                        $qq->where('paterno', 'LIKE', "%{$palabras[1]}%")
                           ->where('materno', 'LIKE', "%{$palabras[0]}%");
                    });
                }
                $q->orWhere('paterno', 'LIKE', "%{$texto}%")
                  ->orWhere('materno', 'LIKE', "%{$texto}%")
                  ->orWhere('nombres', 'LIKE', "%{$texto}%")
                  ->orWhere('ci', 'LIKE', "%{$texto}%")
                  ->orWhere('boleta_pago', 'LIKE', "%{$texto}%");
            });
        }

        if (!empty($filtros['grado'])) {
            $query->where('grado', $filtros['grado']);
        }
        if (!empty($filtros['situacion'])) {
            $query->where('situacion_actual', $filtros['situacion']);
        }
        if (!empty($filtros['estado'])) {
            $query->where('estado_socio', $filtros['estado']);
        }

        return $query->orderBy('paterno')->orderBy('materno')->paginate($porPagina);
    }

    public function buscarPorId(int $id): ?SocioTitular
    {
        return SocioTitular::with(['beneficiarios', 'credenciales', 'documentosRespaldos'])->find($id);
    }

    public function crear(array $datos): SocioTitular
    {
        return SocioTitular::create($datos);
    }

    public function actualizar(int $id, array $datos): SocioTitular
    {
        $socio = SocioTitular::findOrFail($id);
        $socio->update($datos);
        return $socio;
    }

    public function eliminar(int $id): bool
    {
        $socio = SocioTitular::findOrFail($id);
        return (bool) $socio->delete();
    }
}

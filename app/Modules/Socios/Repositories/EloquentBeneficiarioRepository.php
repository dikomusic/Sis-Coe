<?php

namespace App\Modules\Socios\Repositories;

use App\Shared\Repositories\BaseEloquentRepository;
use App\Modules\Socios\Contracts\BeneficiarioRepositoryInterface;
use App\Models\BeneficiarioTitular;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EloquentBeneficiarioRepository extends BaseEloquentRepository implements BeneficiarioRepositoryInterface
{
    protected function getModel(): Model
    {
        return new BeneficiarioTitular();
    }

    public function listarPorTitular(int $titularId): Collection
    {
        return BeneficiarioTitular::where('id_titular', $titularId)->get();
    }

    public function listarTodosPaginados(array $filtros, int $porPagina = 15): LengthAwarePaginator
    {
        $query = BeneficiarioTitular::with('socioTitular');

        if (!empty($filtros['buscar'])) {
            $texto = trim($filtros['buscar']);
            $query->where(function ($q) use ($texto) {
                $q->where('nombres', 'LIKE', "%{$texto}%")
                  ->orWhere('paterno', 'LIKE', "%{$texto}%")
                  ->orWhere('materno', 'LIKE', "%{$texto}%")
                  ->orWhere('ci', 'LIKE', "%{$texto}%");
            });
        }

        return $query->orderBy('paterno')->orderBy('materno')->paginate($porPagina);
    }

    public function buscarPorId(int $id): ?BeneficiarioTitular
    {
        return BeneficiarioTitular::with(['socioTitular', 'credenciales', 'documentosRespaldos'])->find($id);
    }

    public function crear(array $datos): BeneficiarioTitular
    {
        return BeneficiarioTitular::create($datos);
    }

    public function actualizar(int $id, array $datos): BeneficiarioTitular
    {
        $beneficiario = BeneficiarioTitular::findOrFail($id);
        $beneficiario->update($datos);
        return $beneficiario;
    }

    public function eliminar(int $id): bool
    {
        $beneficiario = BeneficiarioTitular::findOrFail($id);
        return (bool) $beneficiario->delete();
    }
}

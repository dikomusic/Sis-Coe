<?php

namespace App\Modules\Consultas\Repositories;

use App\Modules\Consultas\Contracts\BusquedaGeneralRepositoryInterface;
use App\Models\Credencial;
use App\Models\SocioTitular;
use App\Models\BeneficiarioTitular;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class EloquentBusquedaGeneralRepository implements BusquedaGeneralRepositoryInterface
{
    public function buscarCredencialPorId(int|string $codigo): ?Credencial
    {
        return Credencial::where('id', $codigo)->latest('fecha_emision')->first();
    }

    public function buscarSociosPorTexto(string $texto, int $porPagina = 15): LengthAwarePaginator
    {
        return SocioTitular::where(function ($query) use ($texto) {
            $query->where('ci', 'like', "%{$texto}%")
                ->orWhere('nombres', 'like', "%{$texto}%")
                ->orWhere(DB::raw("CONCAT(paterno,' ',materno)"), 'like', "%{$texto}%")
                ->orWhere(DB::raw("CONCAT(nombres,' ',paterno,' ',materno)"), 'like', "%{$texto}%")
                ->orWhere('boleta_pago', 'like', "%{$texto}%");
        })->paginate($porPagina);
    }

    public function buscarBeneficiariosPorTexto(string $texto, int $porPagina = 15): LengthAwarePaginator
    {
        return BeneficiarioTitular::with('socioTitular')->where(function ($query) use ($texto) {
            $query->where('ci', 'like', "%{$texto}%")
                ->orWhere('nombres', 'like', "%{$texto}%")
                ->orWhere(DB::raw("CONCAT(paterno,' ',materno)"), 'like', "%{$texto}%")
                ->orWhere(DB::raw("CONCAT(nombres,' ',paterno,' ',materno)"), 'like', "%{$texto}%");
        })->paginate($porPagina);
    }

    public function obtenerDetalleEntidad(string $tipo, int $id): array
    {
        $tipoNormalizado = strtolower($tipo);
        if ($tipoNormalizado === 'socio' || $tipoNormalizado === 'titular') {
            $entidad = SocioTitular::where('id_titular', $id)->first();
            $credencial = $entidad ? Credencial::where('entidad_id', $entidad->id_titular)
                ->where('entidad_type', SocioTitular::class)
                ->latest('fecha_emision')
                ->first() : null;
        } else {
            $entidad = BeneficiarioTitular::find($id);
            $credencial = $entidad ? Credencial::where('entidad_id', $entidad->id_beneficiario)
                ->where('entidad_type', BeneficiarioTitular::class)
                ->latest('fecha_emision')
                ->first() : null;
        }

        return [
            'entidad' => $entidad,
            'credencial' => $credencial,
        ];
    }
}

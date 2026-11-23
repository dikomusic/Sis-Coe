<?php

namespace App\Modules\Consultas\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Consultas\Contracts\BusquedaGeneralRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConsultaBeneficiarioController extends Controller
{
    public function __construct(
        protected BusquedaGeneralRepositoryInterface $busquedaRepo
    ) {}

    public function buscar(Request $request): View
    {
        $q = trim($request->input('q', ''));
        $beneficiarios = $this->busquedaRepo->buscarBeneficiariosPorTexto($q, 15);
        $beneficiarios->appends($request->query());
        $socios = collect();
        $mode = 'TODOS';
        $tipo = 'BENEFICIARIO';

        return view('pages.consultas.buscar', compact('beneficiarios', 'socios', 'mode', 'tipo', 'q'));
    }

    public function show(int $id): View
    {
        $detalle = $this->busquedaRepo->obtenerDetalleEntidad('beneficiario', $id);
        $entidad = $detalle['entidad'];
        $credencial = $detalle['credencial'];
        $mensajeError = !$credencial ? 'Este beneficiario no tiene credencial activa.' : null;

        return view('pages.consultas.detalle', compact('entidad', 'credencial', 'mensajeError'));
    }
}

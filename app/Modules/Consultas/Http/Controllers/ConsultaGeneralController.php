<?php

namespace App\Modules\Consultas\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Consultas\Contracts\BusquedaGeneralRepositoryInterface;
use App\Modules\Consultas\Services\BusquedaGeneralService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConsultaGeneralController extends Controller
{
    public function __construct(
        protected BusquedaGeneralRepositoryInterface $busquedaRepo,
        protected BusquedaGeneralService $busquedaService
    ) {}

    public function index(): View
    {
        return view('pages.consultas.index');
    }

    public function buscar(Request $request): View
    {
        $request->validate([
            'codigo' => 'required'
        ]);

        $res = $this->busquedaService->verificarCredencial($request->codigo);

        return view('pages.consultas.resultado', [
            'credencial'   => $res['credencial'],
            'entidad'      => $res['entidad'],
            'mensajeError' => $res['mensajeError'],
        ]);
    }

    public function search(Request $request): View
    {
        $mode = $request->input('mode', 'TODOS');
        $tipo = $request->input('tipo', 'SOCIO');
        $q    = trim($request->input('q', ''));

        if ($mode === 'ID' && !empty($q)) {
            $detalle = $this->busquedaRepo->obtenerDetalleEntidad($tipo, (int)$q);
            $entidad = $detalle['entidad'];
            $credencial = $detalle['credencial'];
            $mensajeError = null;

            if (!$entidad) {
                $mensajeError = 'ID no encontrado en ' . ($tipo === 'SOCIO' ? 'socios' : 'beneficiarios') . '.';
            } elseif (!$credencial) {
                $mensajeError = 'Este ' . ($tipo === 'SOCIO' ? 'socio' : 'beneficiario') . ' no tiene ninguna credencial generada.';
            }

            return view('pages.consultas.detalle', compact('entidad', 'credencial', 'mensajeError'));
        }

        $socios = collect();
        $beneficiarios = collect();

        if ($tipo === 'SOCIO') {
            $socios = $this->busquedaRepo->buscarSociosPorTexto($q, 15);
            $socios->appends($request->query());
        } else {
            $beneficiarios = $this->busquedaRepo->buscarBeneficiariosPorTexto($q, 15);
            $beneficiarios->appends($request->query());
        }

        return view('pages.consultas.buscar', compact('socios', 'beneficiarios', 'mode', 'tipo', 'q'));
    }

    public function detalle($id, $tipo): View
    {
        $detalle = $this->busquedaRepo->obtenerDetalleEntidad($tipo, (int)$id);
        $entidad = $detalle['entidad'];
        $credencial = $detalle['credencial'];

        $mensajeError = null;
        if (!$entidad) {
            abort(404, 'Registro no encontrado');
        }
        if (!$credencial) {
            $mensajeError = 'Este ' . $tipo . ' no tiene ninguna credencial generada.';
        }

        return view('pages.consultas.detalle', compact('entidad', 'credencial', 'mensajeError'));
    }

    public function show(int $id): View
    {
        return $this->detalle($id, 'socio');
    }
}

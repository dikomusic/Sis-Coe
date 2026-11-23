<?php

namespace App\Modules\Consultas\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Consultas\Contracts\BusquedaGeneralRepositoryInterface;
use App\Modules\Consultas\Services\BusquedaGeneralService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConsultaCredencialController extends Controller
{
    public function __construct(
        protected BusquedaGeneralService $busquedaService
    ) {}

    public function buscar(Request $request): View
    {
        $codigo = trim($request->input('codigo', $request->input('q', '')));
        if (empty($codigo)) {
            return view('pages.consultas.index');
        }

        $res = $this->busquedaService->verificarCredencial($codigo);

        return view('pages.consultas.resultado', [
            'credencial'   => $res['credencial'],
            'entidad'      => $res['entidad'],
            'mensajeError' => $res['mensajeError'],
        ]);
    }
}

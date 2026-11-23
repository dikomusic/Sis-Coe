<?php

namespace App\Modules\Socios\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Socios\Contracts\BeneficiarioRepositoryInterface;
use App\Modules\Socios\Contracts\SocioRepositoryInterface;
use App\Modules\Socios\Services\BeneficiarioService;
use App\Models\BeneficiarioTitular;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class BeneficiarioTitularController extends Controller
{
    public function __construct(
        protected BeneficiarioRepositoryInterface $beneficiarioRepo,
        protected SocioRepositoryInterface $socioRepo,
        protected BeneficiarioService $beneficiarioService
    ) {}

    public function index($id_titular = null): View
    {
        if ($id_titular) {
            $titular = $this->socioRepo->buscarPorId((int)$id_titular);
            $beneficiarios = $this->beneficiarioRepo->listarPorTitular((int)$id_titular);
            return view('pages.beneficiarios.index', compact('beneficiarios', 'titular'));
        }

        $beneficiarios = $this->beneficiarioRepo->listarTodosPaginados(request()->all(), 15);
        $beneficiarios->appends(request()->query());
        return view('pages.beneficiarios.index', compact('beneficiarios'));
    }

    public function create($id_titular = null): View
    {
        $titular = $id_titular ? $this->socioRepo->buscarPorId((int)$id_titular) : null;
        return view('pages.beneficiarios.create', compact('titular'));
    }

    public function store(Request $request, $id_titular = null): RedirectResponse
    {
        $request->validate([
            'nombres'    => 'required|string|max:255',
            'paterno'    => 'required|string|max:255',
            'parentesco' => 'required|string',
            'foto'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $datos = $request->all();
        if ($id_titular) {
            $datos['id_titular'] = $id_titular;
        }

        $this->beneficiarioService->registrarBeneficiario($datos, $request->file('foto'));

        $redireccion = $id_titular 
            ? redirect()->route('admin.socio.beneficiario.index', $id_titular)
            : redirect()->route('beneficiarios.index');

        return $redireccion->with('success', 'Beneficiario registrado exitosamente.');
    }

    public function edit($id_titular, $id = null): View
    {
        $beneficiarioId = $id ?: $id_titular;
        $beneficiario = $this->beneficiarioRepo->buscarPorId((int)$beneficiarioId);
        if (!$beneficiario) abort(404);

        $titular = $beneficiario->socioTitular;
        return view('pages.beneficiarios.edit', compact('beneficiario', 'titular'));
    }

    public function update(Request $request, $id_titular, $id = null): RedirectResponse
    {
        $beneficiarioId = $id ?: $id_titular;

        $request->validate([
            'nombres' => 'required|string|max:255',
            'paterno' => 'required|string|max:255',
            'foto'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $this->beneficiarioService->actualizarBeneficiario(
            (int)$beneficiarioId,
            $request->all(),
            $request->file('foto'),
            $request->has('borrar_foto')
        );

        $beneficiario = $this->beneficiarioRepo->buscarPorId((int)$beneficiarioId);
        $redirectUrl = $beneficiario?->id_titular 
            ? route('admin.socio.beneficiario.index', $beneficiario->id_titular)
            : route('beneficiarios.index');

        return redirect($redirectUrl)->with('success', 'Beneficiario actualizado correctamente.');
    }

    public function destroy($id_titular, $id = null): RedirectResponse
    {
        $beneficiarioId = $id ?: $id_titular;
        $beneficiario = $this->beneficiarioRepo->buscarPorId((int)$beneficiarioId);
        $titularId = $beneficiario?->id_titular;

        $this->beneficiarioService->eliminarBeneficiario((int)$beneficiarioId);

        $redirectUrl = $titularId 
            ? route('admin.socio.beneficiario.index', $titularId)
            : route('beneficiarios.index');

        return redirect($redirectUrl)->with('success', 'Beneficiario eliminado correctamente.');
    }
}

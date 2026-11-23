<?php

namespace App\Modules\Credenciales\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Credenciales\Contracts\CredencialRepositoryInterface;
use App\Modules\Credenciales\Services\CredencialService;
use App\Models\SocioTitular;
use App\Models\BeneficiarioTitular;
use App\Models\Credencial;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CredencialController extends Controller
{
    public function __construct(
        protected CredencialRepositoryInterface $credencialRepo,
        protected CredencialService $credencialService
    ) {}

    public function create(string $tipo, int $id): View
    {
        $tipoNormalizado = ($tipo === 'socio' || $tipo === 'titular') ? 'socio' : 'beneficiario';
        $entidadClass = $tipoNormalizado === 'socio' ? SocioTitular::class : BeneficiarioTitular::class;

        $model = $tipoNormalizado === 'socio'
            ? SocioTitular::with('credenciales')->findOrFail($id)
            : BeneficiarioTitular::with('credenciales')->findOrFail($id);

        $credenciales = $this->credencialRepo->listarPorEntidad($entidadClass, $id);

        return view('pages.credenciales.create', compact('model', 'tipo', 'id', 'credenciales'));
    }

    public function store(Request $request, string $tipo, int $id): RedirectResponse
    {
        $request->validate([
            'fecha_emision'     => 'required|date',
            'estado'            => 'required|in:activo,inactivo',
            'estado_credencial' => 'required|string',
            'imagen_frontal'    => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'imagen_trasera'    => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        $this->credencialService->emitirCredencial(
            $tipo,
            $id,
            $request->all(),
            $request->file('imagen_frontal'),
            $request->file('imagen_trasera')
        );

        return back()->with('success', 'Credencial emitida correctamente.');
    }

    public function imprimir(string $tipo, int $id): View|RedirectResponse
    {
        $tipoNormalizado = ($tipo === 'socio' || $tipo === 'titular') ? 'socio' : 'beneficiario';
        $entidadClass = $tipoNormalizado === 'socio' ? SocioTitular::class : BeneficiarioTitular::class;

        $model = $tipoNormalizado === 'socio'
            ? SocioTitular::findOrFail($id)
            : BeneficiarioTitular::with('socioTitular')->findOrFail($id);

        $credencial = $this->credencialRepo->obtenerUltimaPorEntidad($entidadClass, $id);

        if (!$credencial) {
            return back()->with('error', 'Esta persona no tiene una credencial emitida.');
        }

        return view('pages.credenciales.imprimir', compact('model', 'credencial', 'tipo'));
    }

    public function edit(string $tipo, int $id, Credencial $credencial): View
    {
        $tipoNormalizado = ($tipo === 'socio' || $tipo === 'titular') ? 'socio' : 'beneficiario';
        $model = $tipoNormalizado === 'socio'
            ? SocioTitular::findOrFail($id)
            : BeneficiarioTitular::findOrFail($id);

        return view('pages.credenciales.edit', compact('model', 'credencial', 'tipo', 'id'));
    }

    public function update(Request $request, string $tipo, int $id, Credencial $credencial): RedirectResponse
    {
        $request->validate([
            'estado'            => 'required|in:activo,inactivo',
            'estado_credencial' => 'required|string',
        ]);

        $this->credencialRepo->actualizar($credencial->id, [
            'estado'            => $request->estado,
            'estado_credencial' => $request->estado_credencial,
        ]);

        return redirect()->route('credenciales.create', ['tipo' => $tipo, 'id' => $id])
            ->with('success', 'Estado de la credencial actualizado.');
    }
}

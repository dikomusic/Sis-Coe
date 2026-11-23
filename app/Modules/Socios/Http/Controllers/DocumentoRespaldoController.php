<?php

namespace App\Modules\Socios\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Socios\Contracts\DocumentoRepositoryInterface;
use App\Modules\Socios\Contracts\SocioRepositoryInterface;
use App\Modules\Socios\Contracts\BeneficiarioRepositoryInterface;
use App\Modules\Socios\Services\DocumentoService;
use App\Models\DocumentoRespaldo;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DocumentoRespaldoController extends Controller
{
    public function __construct(
        protected DocumentoRepositoryInterface $docRepo,
        protected SocioRepositoryInterface $socioRepo,
        protected BeneficiarioRepositoryInterface $beneficiarioRepo,
        protected DocumentoService $docService
    ) {}

    public function create(string $tipo, int $id): View
    {
        $persona = $tipo === 'titular' 
            ? $this->socioRepo->buscarPorId($id) 
            : $this->beneficiarioRepo->buscarPorId($id);

        if (!$persona) abort(404);

        return view('pages.documentos.create', compact('tipo', 'id', 'persona'));
    }

    public function store(Request $request, string $tipo, int $id): RedirectResponse
    {
        $request->validate([
            'tipo_documento' => 'required|string',
            'archivo'        => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'descripcion'    => 'nullable|string|max:500',
        ]);

        $datos = [
            'tipo_documento' => $request->tipo_documento,
            'descripcion'    => $request->descripcion,
            'id_titular'     => $tipo === 'titular' ? $id : null,
            'id_beneficiario'=> $tipo === 'beneficiario' ? $id : null,
        ];

        $this->docService->subirDocumento($datos, $request->file('archivo'));

        $redirect = $tipo === 'titular' 
            ? redirect()->route('admin.socio.index')
            : redirect()->route('beneficiarios.index');

        return $redirect->with('success', 'Documento de respaldo cargado exitosamente.');
    }

    public function edit(int $documento): View
    {
        $doc = $this->docRepo->buscarPorId($documento);
        if (!$doc) abort(404);

        return view('pages.documentos.edit', compact('doc'));
    }

    public function update(Request $request, int $documento): RedirectResponse
    {
        $request->validate([
            'tipo_documento' => 'required|string',
            'archivo'        => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'descripcion'    => 'nullable|string|max:500',
        ]);

        $this->docService->actualizarDocumento($documento, $request->all(), $request->file('archivo'));

        return redirect()->route('admin.socio.index')->with('success', 'Documento actualizado correctamente.');
    }
}

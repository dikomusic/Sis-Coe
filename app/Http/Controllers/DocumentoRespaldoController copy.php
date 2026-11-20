<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\DocumentoRespaldo;
use App\Models\SocioTitular;
use App\Models\BeneficiarioTitular;

class DocumentoRespaldoController extends Controller
{
public function create($tipo, $id)
{
    if ($tipo === 'socio') {
        $model = SocioTitular::with('documentos')->findOrFail($id);
    } else {
        // FORZAR LA CARGA DE LA RELACIÓN
        $model = BeneficiarioTitular::findOrFail($id);
        $model->load('documentos'); // ← ESTO ES LA CLAVE
    }

    return view('documentos.create', compact('model', 'tipo', 'id'));
}

    public function store(Request $request, $tipo, $id)
    {
        // Usar findOrFail sin with aquí, ya que solo necesitamos el modelo para crear
        $model = $tipo === 'socio' 
            ? SocioTitular::findOrFail($id)
            : BeneficiarioTitular::findOrFail($id);

        $request->validate([
            'tipo_documento' => 'required|in:COSSMI,CI_FRONTAL,CI_POSTERIOR,CERTIFICADO_NACIMIENTO,CERTIFICADO_MATRIMONIO,CERTIFICADO_DIVORCIO,CERTIFICADO_DEFUNCION,OTRO',
            'archivo' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'descripcion' => 'nullable|string|max:255'
        ]);

        $ruta = $request->file('archivo')->store('documentos', 'public');

        $model->documentos()->create([
            'tipo_documento' => $request->tipo_documento,
            'ruta_archivo' => $ruta,
            'descripcion' => $request->descripcion,
            'creado_por' => auth()->id()
        ]);

        return back()->with('success', 'Documento de respaldo subido correctamente');
    }
}
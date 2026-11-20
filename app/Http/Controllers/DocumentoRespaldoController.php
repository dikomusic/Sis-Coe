<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
        $model = $tipo === 'socio' 
            ? SocioTitular::findOrFail($id)
            : BeneficiarioTitular::findOrFail($id);

        $request->validate([
            'tipo_documento' => 'required|in:COSSMI,CI_FRONTAL,CI_POSTERIOR,CERTIFICADO_NACIMIENTO,CERTIFICADO_MATRIMONIO,CERTIFICADO_DIVORCIO,CERTIFICADO_DEFUNCION,BOLETA_PAGO,RESOLUCION_BAJA,OTRO',
            'archivo' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'descripcion' => 'nullable|string|max:255'
        ]);

        // ====== GENERAR NOMBRE MILITAR PROFESIONAL ======
        $tipoDoc = str_replace('_', '-', $request->tipo_documento);
        $paterno = Str::slug($model->paterno ?? 'SIN-PATERNO');
        $materno = Str::slug($model->materno ?? 'SIN-MATERNO');
        $nombres = Str::slug($model->nombres ?? 'SIN-NOMBRES');
        $ownerId = $model->id ?? $model->id_titular;

        $baseName = "{$tipoDoc}_{$paterno}_{$materno}_{$nombres}_{$ownerId}";
        $extension = $request->file('archivo')->getClientOriginalExtension();
        $fileName = $baseName . '.' . $extension;

        // Evitar duplicados (agregar _1, _2, etc.)
        $i = 1;
        $finalName = $fileName;
        while (Storage::disk('public')->exists("documentos/{$finalName}")) {
            $finalName = "{$baseName}_{$i}.{$extension}";
            $i++;
        }

        // Guardar archivo
        $ruta = $request->file('archivo')->storeAs('documentos', $finalName, 'public');

        // Guardar en base de datos
        $model->documentos()->create([
            'tipo_documento' => $request->tipo_documento,
            'ruta_archivo' => $ruta,
            'descripcion' => $request->descripcion,
            'creado_por' => auth()->id()
        ]);

        return back()->with('success', 'Documento de respaldo subido correctamente');
    }

    public function edit(DocumentoRespaldo $documento)
    {
        // Obtener el dueño (socio o beneficiario)
        $model = $documento->documentable;
        $tipo = $documento->documentable_type === SocioTitular::class ? 'socio' : 'beneficiario';
        $id = $documento->documentable_id;

        return view('documentos.edit', compact('documento', 'model', 'tipo', 'id'));
    }

    public function update(Request $request, DocumentoRespaldo $documento)
    {
        $request->validate([
            'tipo_documento' => 'required|in:COSSMI,CI_FRONTAL,CI_POSTERIOR,CERTIFICADO_NACIMIENTO,CERTIFICADO_MATRIMONIO,CERTIFICADO_DIVORCIO,CERTIFICADO_DEFUNCION,BOLETA_PAGO,RESOLUCION_BAJA,OTRO',
            'archivo' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'descripcion' => 'nullable|string|max:255'
        ]);

        $data = [
            'tipo_documento' => $request->tipo_documento,
            'descripcion' => $request->descripcion,
        ];

        // SI SUBIÓ NUEVO ARCHIVO → BORRAR EL VIEJO
        if ($request->hasFile('archivo')) {
            // Borrar archivo anterior
            if ($documento->ruta_archivo && Storage::disk('public')->exists($documento->ruta_archivo)) {
                Storage::disk('public')->delete($documento->ruta_archivo);
            }

            // Generar nuevo nombre militar
            $model = $documento->documentable;
            $tipoDoc = str_replace('_', '-', $request->tipo_documento);
            $paterno = Str::slug($model->paterno ?? 'SIN-PATERNO');
            $materno = Str::slug($model->materno ?? 'SIN-MATERNO');
            $nombres = Str::slug($model->nombres ?? 'SIN-NOMBRES');
            $ownerId = $model->id ?? $model->id_titular;

            $baseName = "{$tipoDoc}_{$paterno}_{$materno}_{$nombres}_{$ownerId}";
            $extension = $request->file('archivo')->getClientOriginalExtension();
            $fileName = $baseName . '.' . $extension;

            $i = 1;
            $finalName = $fileName;
            while (Storage::disk('public')->exists("documentos/{$finalName}")) {
                $finalName = "{$baseName}_{$i}.{$extension}";
                $i++;
            }

            $ruta = $request->file('archivo')->storeAs('documentos', $finalName, 'public');
            $data['ruta_archivo'] = $ruta;
        }

        $documento->update($data);

        return redirect()
        ->route('documentos.create', [
            'tipo' => $documento->documentable_type === SocioTitular::class ? 'socio' : 'beneficiario',
            'id'   => $documento->documentable_id
        ])
        ->with('success', 'Documento actualizado correctamente');
    
        if ($request->hasFile('archivo')) {

        $ruta = $request->file('archivo')->store('documentos', 'public');

        DocumentoRespaldo::create([
            'documentable_id' => $id,
            'documentable_type' => $tipo == 'socio'
                ? SocioTitular::class
                : BeneficiarioTitular::class,
            'tipo_documento' => 'COSSMIL',
            'ruta_archivo' => $ruta
        ]);
        }
    
        }




}
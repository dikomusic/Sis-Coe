<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{SocioTitular, BeneficiarioTitular, Credencial};
use Carbon\Carbon;

class CredencialController extends Controller
{
    public function create($tipo, $id)
    {
        $model = $tipo === 'socio'
            ? SocioTitular::with('credenciales')->findOrFail($id)
            : BeneficiarioTitular::with('credenciales')->findOrFail($id);

        return view('credenciales.create', compact('model', 'tipo', 'id'));
    }

public function store(Request $request, $tipo, $id)
{
    $model = $tipo === 'socio'
        ? SocioTitular::findOrFail($id)
        : BeneficiarioTitular::findOrFail($id);

    $request->validate([
        'fecha_emision' => 'required|date',
        'estado' => 'required|in:activo,inactivo',
        'estado_credencial' => 'required|string',
        'imagen_frontal' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        'imagen_trasera' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
    ]);

    $emision = Carbon::parse($request->fecha_emision);
    $vencimiento = $emision->copy()->addYears(5);


// ==========================================
// VENCIMIENTO PARA TITULARES
// ==========================================

if ($tipo === 'socio') {

    // =========================================
    // SOCIO PASIVO = CREDENCIAL INDEFINIDA
    // =========================================
    if (
        strtoupper($model->situacion_actual ?? '') === 'PASIVO'
    ) {

        // Puedes usar null o una fecha larga
        $vencimiento = Carbon::create(2099, 12, 31);

    } 
    // =========================================
    // ACTIVO / RESERVA ACTIVA
    // =========================================
    elseif ($model->fecha_alta_ejercito) {

        $fechaAlta = Carbon::parse($model->fecha_alta_ejercito);

        // Años transcurridos desde alta
        $aniosTranscurridos = $fechaAlta->diffInYears($emision);

        // Bloque de ascenso cada 5 años
        $bloque = floor($aniosTranscurridos / 5);

        // Fin del bloque actual
        $vencimiento = $fechaAlta
            ->copy()
            ->addYears(($bloque + 1) * 5);
    } 
    else {

        $vencimiento = $emision->copy()->addYears(5);
    }

} else {

    // BENEFICIARIOS
    $vencimiento = $emision->copy()->addYears(5);
}
// ==========================================
// REGLA PARA HIJOS
// ==========================================

if ($tipo === 'beneficiario' &&
    in_array($model->parentesco, ['HIJO','HIJA','HIJASTRO','HIJASTRA']) &&
    $model->fecha_nacimiento) {

    $cumple28 = Carbon::parse($model->fecha_nacimiento)->addYears(28);

    if ($vencimiento->gt($cumple28)) {
        $vencimiento = $cumple28;
    }
}

    $data = [
        'entidad_type' => $tipo === 'socio' ? SocioTitular::class : BeneficiarioTitular::class,
        'entidad_id' => $tipo === 'socio'
    ? $model->id_titular
    : $model->id,
    
        'fecha_emision' => $emision,
        'fecha_vencimiento' => $vencimiento,
        'estado' => $request->estado,
        'estado_credencial' => $request->estado_credencial,
    ];

    // SUBIR IMAGEN FRONTAL
    if ($request->hasFile('imagen_frontal')) {
        $data['imagen_frontal'] = $request->file('imagen_frontal')->store('credenciales', 'public');
    }

    // SUBIR IMAGEN TRASERA
    if ($request->hasFile('imagen_trasera')) {
        $data['imagen_trasera'] = $request->file('imagen_trasera')->store('credenciales', 'public');
    }

    Credencial::create($data);

    return back()->with('success', 'Credencial generada correctamente');
}


public function imprimir($tipo, $id)
{
    if ($tipo === 'socio') {

        $model = SocioTitular::where('id_titular', $id)->firstOrFail();

        $credencial = Credencial::where('entidad_type', SocioTitular::class)
            ->where('entidad_id', $id)
            ->orderBy('id', 'desc')
            ->first();

    } else {

        $model = BeneficiarioTitular::findOrFail($id);

        $credencial = Credencial::where('entidad_type', BeneficiarioTitular::class)
            ->where('entidad_id', $id)
            ->orderBy('id', 'desc')
            ->first();
    }

    dd($credencial);

    if (!$credencial) {
        return back()->with('warning', 'No existe ninguna credencial registrada.');
    }

    return view('credenciales.imprimir', compact('model', 'credencial', 'tipo'));
}




public function edit($tipo, $id, Credencial $credencial)
{
    $model = $tipo === 'socio'
        ? SocioTitular::findOrFail($id)
        : BeneficiarioTitular::findOrFail($id);

    // Verificar que la credencial pertenece al modelo
    if ($credencial->entidad_id != $id || $credencial->entidad_type != ($tipo === 'socio' ? SocioTitular::class : BeneficiarioTitular::class)) {
        abort(404);
    }

    return view('credenciales.edit', compact('model', 'credencial', 'tipo', 'id'));
}

public function update(Request $request, $tipo, $id, Credencial $credencial)
{
    $request->validate([
        'estado' => 'required|in:activo,inactivo',
        'estado_credencial' => 'required|string',
    ]);

    $credencial->update([
        'estado' => $request->estado,
        'estado_credencial' => $request->estado_credencial,
    ]);

    // REDIRECCIÓN AUTOMÁTICA A LA LISTA DE CREDENCIALES
    return redirect()
        ->route('credenciales.create', [
            'tipo' => $tipo,
            'id'   => $id
        ])
        ->with('success', 'Credencial actualizada correctamente');
}



}
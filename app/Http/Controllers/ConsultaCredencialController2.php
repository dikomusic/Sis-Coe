<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Credencial;

class ConsultaCredencialController extends Controller
{
public function buscar(Request $request)
{
    $request->validate([
        'codigo' => 'required'
    ]);

    $credencial = Credencial::where('id', $request->codigo)->first();

    if (!$credencial) {
        return back()->with('error', 'Credencial no encontrada');
    }

    $entidad = $credencial->entidad;

    // Si está inactiva, puedes pasar una bandera para la vista
    $esInactiva = $credencial->estado !== 'activo';

    return view('consultas.resultado', compact('credencial', 'entidad', 'esInactiva'));
}
}

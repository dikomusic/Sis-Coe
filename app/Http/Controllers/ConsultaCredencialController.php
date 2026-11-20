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

        // Busca la credencial (sin filtro de estado por ahora)
        $credencial = Credencial::where('id', $request->codigo)->first();

        $entidad = null;
        $mensajeError = null;

        if ($credencial) {
            $entidad = $credencial->entidad;

            // Si está inactiva, puedes pasar un flag o mensaje
            if ($credencial->estado !== 'activo') {
                $mensajeError = 'La credencial está INACTIVA. Motivo: ' . ($credencial->estado_credencial ?? 'No especificado');
            }
        } else {
            $mensajeError = 'Credencial INEXISTENTE o no encontrada con el código ingresado.';
        }

        return view('consultas.resultado', compact('credencial', 'entidad', 'mensajeError'));
    }
}
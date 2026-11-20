<?php

namespace App\Http\Controllers;

use App\Models\SocioTitular;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class SocioTitularController extends Controller
{
public function index()
{
    $query = SocioTitular::query();

    // === BÚSQUEDA AVANZADA POR APELLIDOS (Paterno + Materno) ===
    if ($texto = trim(request('buscar'))) {
        $palabras = preg_split('/\s+/', $texto, 2); // Divide en máximo 2 palabras

        $query->where(function ($q) use ($texto, $palabras) {
            // Caso 1: escribió dos apellidos → busca "Paterno Materno" o "Materno Paterno"
            if (count($palabras) === 2) {
                $q->where(function ($qq) use ($palabras) {
                    $qq->where('paterno', 'LIKE', "%{$palabras[0]}%")
                       ->where('materno', 'LIKE', "%{$palabras[1]}%");
                })->orWhere(function ($qq) use ($palabras) {
                    $qq->where('paterno', 'LIKE', "%{$palabras[1]}%")
                       ->where('materno', 'LIKE', "%{$palabras[0]}%");
                });
            }

            // Caso 2: busca por cualquier campo individualmente
            $q->orWhere('paterno', 'LIKE', "%{$texto}%")
              ->orWhere('materno', 'LIKE', "%{$texto}%")
              ->orWhere('nombres', 'LIKE', "%{$texto}%")
              ->orWhere('ci', 'LIKE', "%{$texto}%")
              ->orWhere('boleta_pago', 'LIKE', "%{$texto}%");
        });
    }
    // === FIN BÚSQUEDA ===

    // Filtros
    if ($grado = request('grado')) {
        $query->where('grado', $grado);
    }
    if ($situacion = request('situacion')) {
        $query->where('situacion_actual', $situacion);
    }
    if ($estado = request('estado')) {
        $query->where('estado_socio', $estado);
    }

    $socios = $query->orderBy('paterno')->orderBy('materno')->paginate(15);
    $socios->appends(request()->query());

    return view('socio.index', compact('socios'));
}

    public function create()
    {
        return view('socio.create');
    }

public function store(Request $request)
{
    $request->validate([
        'nombres'             => 'required|string|max:255',
        'paterno'             => 'required|string|max:255',
        'materno'             => 'nullable|string|max:255',
        'grado'               => 'required|string',
        'arma_especialidad'   => 'required|string',                    // obligatorio el select
        'arma_especialidad_manual' => 'nullable|string|max:100|required_if:arma_especialidad,OTRO',
        'foto'                => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        // ... tus otras validaciones (ci, boleta, etc.)
    ]);

    $data = $request->all();

    // LÓGICA DE ARMA / ESPECIALIDAD (AQUÍ ES DONDE VA)
    if ($request->arma_especialidad === 'OTRO' && $request->filled('arma_especialidad_manual')) {
        $data['arma_especialidad'] = strtoupper(trim($request->arma_especialidad_manual));
    }
    // Si no es OTRO, Laravel ya trae el valor correcto del select en $data['arma_especialidad']

    // LÓGICA DE FOTO (tu código original – queda igual)
    if ($request->hasFile('foto')) {
        $nombre   = Str::slug($request->nombres);
        $paterno  = Str::slug($request->paterno);
        $materno  = Str::slug($request->materno ?: 'sin-materno');

        $nombreArchivo = "{$nombre}_{$paterno}_{$materno}";
        $extension     = $request->file('foto')->getClientOriginalExtension();
        $nombreFinal   = "{$nombreArchivo}.{$extension}";

        $contador = 1;
        $nombreTemp = $nombreFinal;
        while (Storage::disk('public')->exists("socios/{$nombreTemp}")) {
            $nombreTemp = "{$nombreArchivo}_{$contador}.{$extension}";
            $contador++;
        }

        $ruta = $request->file('foto')->storeAs('socios', $nombreTemp, 'public');
        $data['foto'] = $ruta;
    }

    // Crear el socio
    SocioTitular::create($data);

    return redirect()->route('admin.socio.index')->with('success', 'Socio creado correctamente');
}

public function update(Request $request, $id)
{
    $socio = SocioTitular::findOrFail($id);   // ← Aquí corregido

    $request->validate([
        'nombres'  => 'required|string|max:255',
        'paterno'  => 'required|string|max:255',
        'materno'  => 'required|string|max:255',
        'foto'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
    ]);

    $data = $request->all();

    // MISMA LÓGICA PARA ARMA/ESPECIALIDAD-
    if ($request->arma_especialidad === 'OTRO' && $request->filled('arma_especialidad_manual')) {
        $data['arma_especialidad'] = strtoupper(trim($request->arma_especialidad_manual));
    }
    
    if ($request->hasFile('foto')) {
        // Borrar foto anterior si existe
        if ($socio->foto && Storage::disk('public')->exists($socio->foto)) {
            Storage::disk('public')->delete($socio->foto);
        }

        $nombre   = Str::slug($request->nombres);
        $paterno  = Str::slug($request->paterno);
        $materno  = Str::slug($request->materno);

        $nombreArchivo = "{$nombre}_{$paterno}_{$materno}";
        $extension     = $request->file('foto')->getClientOriginalExtension();
        $nombreFinal   = "{$nombreArchivo}.{$extension}";

        $contador = 1;
        $nombreTemp = $nombreFinal;
        while (Storage::disk('public')->exists("socios/{$nombreTemp}")) {
            $nombreTemp = "{$nombreArchivo}_{$contador}.{$extension}";
            $contador++;
        }

        $ruta = $request->file('foto')->storeAs('socios', $nombreTemp, 'public');
        $data['foto'] = $ruta;
    }

    // Borrar foto si se marcó el checkbox y no se subió una nueva
    if ($request->has('borrar_foto') && !$request->hasFile('foto')) {
        if ($socio->foto && Storage::disk('public')->exists($socio->foto)) {
            Storage::disk('public')->delete($socio->foto);
        }
        $data['foto'] = null;
    }

    $socio->update($data);

    return redirect()->route('admin.socio.index')->with('success', 'Socio actualizado correctamente');
}

    public function edit($id)
    {
        $socio = SocioTitular::findOrFail($id);
        return view('socio.edit', compact('socio'));
    }



    public function destroy($id)
    {
        $socio = SocioTitular::findOrFail($id);

        // Eliminar imagen asociada
        if ($socio->foto && Storage::disk('public')->exists($socio->foto)) {
            Storage::disk('public')->delete($socio->foto);
        }

        $socio->delete();

        return redirect()
            ->route('socio.index')
            ->with('success', 'Socio eliminado correctamente');
    }

    public function ficha($id)
    {
        $socio = SocioTitular::with('fotoCossmil')->findOrFail($id);

        return view('socios.ficha', compact('socio'));
    }

    public function fichaPdf($id)
    {
        $socio = SocioTitular::with('fotoCossmil')->findOrFail($id);

        $pdf = Pdf::loadView('socio.ficha_pdf', compact('socio'))
            ->setPaper('A4', 'portrait');

        return $pdf->download('ficha_socio_'.$socio->id_titular.'.pdf');
    }

}


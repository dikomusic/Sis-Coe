<?php

namespace App\Http\Controllers;

use App\Models\SocioTitular;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SocioTitularController extends Controller
{
    public function index()
    {
        $socios = SocioTitular::all();
        //$socios = SocioTitular::orderBy('paterno')->orderBy('materno')->paginate(15);
        return view('socio.index', compact('socios'));
    }

    public function create()
    {
        return view('socio.create');
    }

public function store(Request $request)
{
    $request->validate([
        'nombres'  => 'required|string|max:255',
        'paterno'  => 'required|string|max:255',
        'materno'  => 'required|string|max:255',
        'foto'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        // ... tus otras validaciones
    ]);

    $data = $request->all();

    if ($request->hasFile('foto')) {
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

    SocioTitular::create($data);   // ← Aquí corregido

    return redirect()->route('socio.index')->with('success', 'Socio creado correctamente');
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

    return redirect()->route('socio.index')->with('success', 'Socio actualizado correctamente');
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
}


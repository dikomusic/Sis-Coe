<?php

namespace App\Http\Controllers;

use App\Models\BeneficiarioTitular;
use App\Models\SocioTitular;
use Illuminate\Http\Request;

class BeneficiarioTitularController extends Controller
{
    /**
     * Mostrar todos los beneficiarios.
     */
   public function index($id_titular)
{
    $socio = SocioTitular::findOrFail($id_titular);

    $beneficiarios = BeneficiarioTitular::where('id_titular', $id_titular)->get();

    return view('beneficiario.index', compact('socio', 'beneficiarios'));
}

public function create($id_titular)
{
    $socio = SocioTitular::findOrFail($id_titular);

    $beneficiario = new BeneficiarioTitular(); // ← agregar esto

    return view('beneficiario.create', compact('socio', 'beneficiario'));
}


public function edit($id_titular, $id)
{
    $socio = SocioTitular::findOrFail($id_titular);
    $beneficiario = BeneficiarioTitular::findOrFail($id);

    return view('beneficiario.edit', compact('socio', 'beneficiario'));
}

    /**
     * Registrar un nuevo beneficiario.
     */
public function store(Request $request, $id_titular)
{
    $request->validate([
        'parentesco'        => 'required|string|max:50',
        'paterno'           => 'nullable|string|max:255',
        'materno'           => 'nullable|string|max:255',
        'nombres'           => 'required|string|max:255',
        'ci'                => 'required|string|max:20',
        'fecha_nacimiento'  => 'required|date',
        'tipo_sangre'       => 'nullable|string|max:10',
        'telf_cel'          => 'nullable|string|max:20',
        'fecha_alta_coe'    => 'nullable|date',
        'foto'              => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        'observacion'       => 'nullable|string',
    ]);

    // NO usamos $request->all()
    $data = $request->only([
        'parentesco', 'paterno', 'materno', 'nombres', 'ci',
        'fecha_nacimiento', 'tipo_sangre', 'telf_cel',
        'fecha_alta_coe', 'observacion'
    ]);

    $data['id_titular'] = $id_titular;
    $data['estado_beneficiario'] = 'ACTIVO';
    $data['estado_por_titular'] = true;
    $data['nota'] = 'ACTIVO';

    // =========================================
    //  GUARDADO DE FOTO — YA SIN EL ERROR
    // =========================================
    if ($request->hasFile('foto')) {

        $nombre   = Str::slug($request->nombres ?? 'nombre');
        $paterno  = Str::slug($request->paterno ?? 'paterno');
        $materno  = Str::slug($request->materno ?? 'materno');

        $baseName = "{$nombre}_{$paterno}_{$materno}";
        $extension = $request->file('foto')->getClientOriginalExtension();

        $nombreFinal = "{$baseName}.{$extension}";
        $tempName = $nombreFinal;

        $contador = 1;
        while (Storage::disk('public')->exists("beneficiarios/{$tempName}")) {
            $tempName = "{$baseName}_{$contador}.{$extension}";
            $contador++;
        }

        $ruta = $request->file('foto')->storeAs('beneficiarios', $tempName, 'public');
        $data['foto'] = $ruta;
    }

    BeneficiarioTitular::create($data);

    return redirect()
        ->route('socio.beneficiario.index', $id_titular)
        ->with('success', 'Beneficiario registrado correctamente.');
}




    /**
     * Mostrar beneficiario por ID.
     */
    public function show($id)
    {
        $beneficiario = BeneficiarioTitular::with('socioTitular')->findOrFail($id);
        return response()->json($beneficiario);
    }

    /**
     * Actualizar beneficiario.
     */
public function update(Request $request, $id_titular, $id)
{
    $beneficiario = BeneficiarioTitular::findOrFail($id);

    $request->validate([
        'parentesco'       => 'required|string|max:50',
        'paterno'          => 'nullable|string|max:255',
        'materno'          => 'nullable|string|max:255',
        'nombres'          => 'required|string|max:255',
        'ci'               => 'required|string|max:20',
        'fecha_nacimiento' => 'required|date',
        'tipo_sangre'      => 'nullable|string|max:10',
        'telf_cel'         => 'nullable|string|max:20',
        'fecha_alta_coe'   => 'nullable|date',
        'foto'             => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        'observacion'      => 'nullable|string',
    ]);

    // DATA LIMPIA
    $data = $request->only([
        'parentesco', 'paterno', 'materno', 'nombres', 'ci',
        'fecha_nacimiento', 'tipo_sangre', 'telf_cel',
        'fecha_alta_coe', 'estado_beneficiario', 'observacion'
    ]);

    // BORRAR FOTO SI MARCÓ EL CHECKBOX
    if ($request->has('borrar_foto') && $beneficiario->foto) {
        Storage::disk('public')->delete($beneficiario->foto);
        $data['foto'] = null;
    }

    // SUBIR NUEVA FOTO
    if ($request->hasFile('foto')) {

        // borrar anterior
        if ($beneficiario->foto) {
            Storage::disk('public')->delete($beneficiario->foto);
        }

        $nombre   = Str::slug($request->nombres);
        $paterno  = Str::slug($request->paterno);
        $materno  = Str::slug($request->materno);

        $baseName = "{$nombre}_{$paterno}_{$materno}";
        $ext = $request->file('foto')->getClientOriginalExtension();

        $filename = "{$baseName}.{$ext}";
        $temp = $filename;
        $i = 1;

        while (Storage::disk('public')->exists("beneficiarios/{$temp}")) {
            $temp = "{$baseName}_{$i}.{$ext}";
            $i++;
        }

        $ruta = $request->file('foto')->storeAs('beneficiarios', $temp, 'public');
        $data['foto'] = $ruta;
    }

    $beneficiario->update($data);

    return redirect()
        ->route('socio.beneficiario.index', $id_titular)
        ->with('success', 'Beneficiario actualizado correctamente.');
}



    /**
     * Eliminar un beneficiario.
     */
    public function destroy($id)
    {
        $beneficiario = BeneficiarioTitular::findOrFail($id);
        $beneficiario->delete();

        return response()->json([
            'message' => 'Beneficiario eliminado correctamente'
        ]);
    }

    /**
     * Deshabilitar beneficiarios automáticamente cuando el socio cambia de estado.
     */
    public function syncEstadoSocio($socioId)
    {
        $socio = SocioTitular::findOrFail($socioId);

        BeneficiarioTitular::where('socio_titular_id', $socioId)
            ->update([
                'estado_por_socio' => $socio->estado_titular == 'activo'
            ]);

        return response()->json([
            'message' => 'Estado de beneficiarios sincronizado con el estado del titular'
        ]);
    }
}

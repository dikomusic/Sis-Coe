<?php

namespace App\Http\Controllers;

use App\Models\BeneficiarioTitular;
use App\Models\SocioTitular;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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
    // REGLAS BASE (siempre)
    $rules = [
        'codigo_cossmil'   => 'nullable|string|max:255',
        'parentesco'       => 'required|string|max:50',
        'paterno'          => 'nullable|string|max:255',
        'materno'          => 'nullable|string|max:255',
        'nombres'          => 'required|string|max:255',
        'ci'               => 'required|string|max:20',
        'fecha_nacimiento' => 'required|date',
        'tipo_sangre'      => 'nullable|string|max:10',
        'telf_cel'         => 'nullable|string|max:20',
        'fecha_alta_coe'   => 'nullable|date',
        'observacion'      => 'nullable|string',
        'nota'             => 'nullable|string',
    ];

    // Solo validar foto si subió archivo
    if ($request->hasFile('foto')) {
        $rules['foto'] = 'image|mimes:jpeg,png,jpg,webp|max:2048';
    }

    $request->validate($rules);

    $data = $request->only([
        'codigo_cossmil', 'parentesco', 'paterno', 'materno', 'nombres', 'ci',
        'fecha_nacimiento', 'tipo_sangre', 'telf_cel', 'fecha_alta_coe', 'observacion', 'nota'
    ]);

    $data['id_titular'] = $id_titular;
    $data['estado_beneficiario'] = 'ACTIVO';
    $data['estado_por_titular'] = true;
    //$data['nota'] = 'ACTIVO';
    
    // SUBIR FOTO (solo si hay archivo)
    if ($request->hasFile('foto')) {
        $nombre  = Str::slug($request->nombres);
        $paterno = Str::slug($request->paterno ?? '');
        $materno = Str::slug($request->materno ?? '');
        $base    = "{$nombre}_{$paterno}_{$materno}";
        $ext     = $request->file('foto')->getClientOriginalExtension();
        $file    = $base . '.' . $ext;
        $i       = 1;
        while (Storage::disk('public')->exists("beneficiarios/{$file}")) {
            $file = "{$base}_{$i}.{$ext}";
            $i++;
        }
        $ruta = $request->file('foto')->storeAs('beneficiarios', $file, 'public');
        $data['foto'] = $ruta;
    }

    //BeneficiarioTitular::create($data);

    // NUEVA LÍNEA – BAJA AUTOMÁTICA POR EDAD
    $beneficiario = BeneficiarioTitular::create($data);
    $beneficiario->verificarBajaPorEdad();


    return redirect()
        ->route('admin.socio.beneficiario.index', $id_titular)
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

    // ====== VALIDACIÓN DINÁMICA ======
    $rules = [
        'codigo_cossmil'   => 'nullable|string|max:255',
        'parentesco'       => 'required|string|max:50',
        'paterno'          => 'nullable|string|max:255',
        'materno'          => 'nullable|string|max:255',
        'nombres'          => 'required|string|max:255',
        'ci'               => 'required|string|max:20',
        'fecha_nacimiento' => 'required|date',
        'tipo_sangre'      => 'nullable|string|max:10',
        'telf_cel'         => 'nullable|string|max:20',
        'fecha_alta_coe'   => 'nullable|date',
        'observacion'      => 'nullable|string',
        'nota'             => 'nullable|string',
    ];

    // Solo validar foto si se subió una nueva
    if ($request->hasFile('foto')) {
        $rules['foto'] = 'image|mimes:jpeg,png,jpg,webp|max:2048';
    }

    $request->validate($rules);

    // ====== DATOS A ACTUALIZAR ======
    $data = $request->only([
        'codigo_cossmil', 'estado_beneficiario', 'parentesco', 'paterno', 'materno', 'nombres', 'ci',
        'fecha_nacimiento', 'tipo_sangre', 'telf_cel', 'fecha_alta_coe', 'observacion', 'nota'
    ]);

    // ====== CASO 1: MARCÓ "ELIMINAR FOTO" ======
    if ($request->has('borrar_foto') && $request->borrar_foto == '1') {
        if ($beneficiario->foto && Storage::disk('public')->exists($beneficiario->foto)) {
            Storage::disk('public')->delete($beneficiario->foto);
        }
        $data['foto'] = null;
    }

    // ====== CASO 2: SUBIÓ FOTO NUEVA ======
    elseif ($request->hasFile('foto')) {
        // Borrar la foto anterior si existe
        if ($beneficiario->foto && Storage::disk('public')->exists($beneficiario->foto)) {
            Storage::disk('public')->delete($beneficiario->foto);
        }

        $nombre  = Str::slug($request->nombres);
        $paterno = Str::slug($request->paterno ?? '');
        $materno = Str::slug($request->materno ?? '');
        $base    = "{$nombre}_{$paterno}_{$materno}";
        $ext     = $request->file('foto')->getClientOriginalExtension();
        $file    = $base . '.' . $ext;
        $i       = 1;

        while (Storage::disk('public')->exists("beneficiarios/{$file}")) {
            $file = "{$base}_{$i}.{$ext}";
            $i++;
        }

        $ruta = $request->file('foto')->storeAs('beneficiarios', $file, 'public');
        $data['foto'] = $ruta;
    }

    // ====== CASO 3: NO TOCÓ NADA → MANTIENE LA FOTO ACTUAL ======
    // (no hacemos nada, se mantiene el valor actual en BD)


    $beneficiario->update($data);

    // Recargar datos actualizados desde la BD
    $beneficiario->refresh();

    // Verificar edad automáticamente
    $beneficiario->verificarBajaPorEdad();

    return redirect()
        ->route('admin.socio.beneficiario.index', $id_titular)
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

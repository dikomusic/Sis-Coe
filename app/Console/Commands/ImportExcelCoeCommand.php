<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use ZipArchive;

class ImportExcelCoeCommand extends Command
{
    protected $signature = 'siscoe:import-excel {--file=storage/app/inscripcion_coe.xlsm} {--dry-run} {--fresh}';
    protected $description = 'Importa el padrón histórico y activo del Excel COE a PostgreSQL';

    public function handle()
    {
        $filePath = $this->option('file');
        $dryRun = $this->option('dry-run');
        $fresh = $this->option('fresh');

        $this->info("Iniciando importación desde: $filePath");
        if (!file_exists($filePath)) {
            $this->error("El archivo no existe: $filePath");
            return 1;
        }

        $zip = new ZipArchive();
        if ($zip->open($filePath) !== true) {
            $this->error("No se pudo abrir el archivo .xlsm como archivo zip.");
            return 1;
        }

        $this->info("Cargando cadenas compartidas (sharedStrings)...");
        $strings = [];
        if ($zip->locateName("xl/sharedStrings.xml") !== false) {
            $xml = simplexml_load_string($zip->getFromName("xl/sharedStrings.xml"));
            foreach ($xml->si as $si) {
                $str = '';
                if (isset($si->t)) {
                    $str = (string)$si->t;
                } elseif (isset($si->r)) {
                    foreach ($si->r as $r) {
                        $str .= (string)$r->t;
                    }
                }
                $strings[] = $str;
            }
        }
        $this->info("Shared strings cargadas: " . count($strings));

        // Read BDCURSOS (sheet3.xml)
        $this->info("Leyendo hoja BDCURSOS...");
        $xmlCursos = simplexml_load_string($zip->getFromName("xl/worksheets/sheet3.xml"));
        $rows = [];

        foreach ($xmlCursos->sheetData->row as $row) {
            $r = (int)$row['r'];
            if ($r <= 5) continue; // headers are in row 5
            $cells = [];
            foreach ($row->c as $c) {
                $ref = (string)$c['r'];
                $col = preg_replace('/[0-9]/', '', $ref);
                $t = (string)$c['t'];
                $v = (string)$c->v;
                if ($t === 's' && isset($strings[(int)$v])) {
                    $v = $strings[(int)$v];
                }
                $cells[$col] = trim($v);
            }

            $nombre = $cells['D'] ?? '';
            $apellido = $cells['E'] ?? '';
            if ($nombre === '' && $apellido === '') {
                continue;
            }

            $rows[] = [
                'row_num' => $r,
                'nombre' => $nombre,
                'apellido' => $apellido,
                'observacion' => $cells['F'] ?? '',
                'disciplina' => $this->normalizeDisciplina($cells['G'] ?? 'NATACION'),
                'modalidad' => trim($cells['H'] ?? 'MENSUAL'),
                'fecha_inicio' => $this->excelDate($cells['I'] ?? null),
                'fecha_fin' => $this->excelDate($cells['J'] ?? null),
                'costo' => (float)($cells['K'] ?? 0),
                'factura' => trim($cells['L'] ?? ''),
                'horario' => trim($cells['M'] ?? '08:00 - 09:00'),
                'profesor' => trim($cells['N'] ?? 'INSTRUCTOR GENERAL'),
                'fecha_ins' => $this->excelDate($cells['O'] ?? null)
            ];
        }

        $zip->close();
        $this->info("Filas válidas encontradas en Excel: " . count($rows));

        if ($dryRun) {
            $this->warn("MODO DRY-RUN ACTIVADO. No se escribirán cambios en la base de datos.");
            return 0;
        }

        DB::beginTransaction();
        try {
            if ($fresh) {
                $this->warn("Limpiando registros previos de inscripciones...");
                DB::table('asistencias_tarjeta')->delete();
                DB::table('tarjetas_inscripcion')->delete();
                DB::table('pagos_facturas')->delete();
                DB::table('reprogramaciones')->delete();
                DB::table('inscripciones')->delete();
            }

            // 1. Disciplinas
            $this->info("Sincronizando disciplinas...");
            $disciplinasMap = [];
            foreach (DB::table('disciplinas')->get() as $d) {
                $disciplinasMap[strtoupper(trim($d->nombre))] = $d->id;
            }

            $capacidadesPorDisciplina = [
                'NATACION' => 12,
                'NATACION NIÑOS' => 10,
                'TENIS' => 10,
                'TAEKWONDO' => 15,
                'BAILE FITNESS' => 30,
                'GIMNASIO' => 25,
                'FRONTON' => 12,
                'BASQUETBALL' => 20,
                'VOLEIBOL' => 20,
                'FUTSAL' => 20,
                'AJEDREZ' => 15,
                'YOGA' => 20,
                'BALLET' => 15,
                'INSTRUMENTOS MUSICALES' => 10,
            ];

            // 2. Instructores
            $this->info("Sincronizando instructores...");
            $instructoresMap = [];
            $existingInst = DB::table('instructores')
                ->join('personas', 'instructores.persona_id', '=', 'personas.id')
                ->select('instructores.id', 'personas.nombres', 'personas.paterno')
                ->get();
            foreach ($existingInst as $ei) {
                $fullName = strtoupper(trim("{$ei->nombres} {$ei->paterno}"));
                $instructoresMap[$fullName] = $ei->id;
            }

            // 3. Modalidades
            $this->info("Sincronizando modalidades...");
            $modalidadesMap = [];
            foreach (DB::table('modalidades')->get() as $m) {
                $modalidadesMap[$m->disciplina_id . '-' . strtoupper(trim($m->nombre))] = $m->id;
            }

            // 4. Tarifas (key: modalidad_id -> tarifa_id)
            $this->info("Sincronizando tarifas...");
            $tarifasMap = [];
            $tarifasPrecioMap = [];
            foreach (DB::table('tarifas')->where('tipo_persona', 'PARTICULAR')->get() as $t) {
                $tarifasMap[$t->modalidad_id] = $t->id;
                $tarifasPrecioMap[$t->modalidad_id] = (float)$t->precio;
            }

            // 5. Horarios Cursos
            $this->info("Sincronizando horarios cursos...");
            $horariosMap = [];
            foreach (DB::table('horarios_cursos')->get() as $hc) {
                $horariosMap[$hc->disciplina_id . '-' . $hc->instructor_id . '-' . substr($hc->hora_inicio, 0, 5)] = $hc->id;
            }

            $instIndex = 100;
            foreach ($rows as $item) {
                $discName = $item['disciplina'];
                if (!isset($disciplinasMap[$discName])) {
                    $newDiscId = DB::table('disciplinas')->insertGetId([
                        'nombre' => $discName,
                        'descripcion' => "Disciplina deportiva COE: $discName",
                        'activo' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $disciplinasMap[$discName] = $newDiscId;
                }
                $discId = $disciplinasMap[$discName];

                // Instructor
                $profName = strtoupper($item['profesor']);
                if ($profName === '' || $profName === 'SIN INSTRUCTOR') {
                    $profName = 'INSTRUCTOR GENERAL';
                }
                if (!isset($instructoresMap[$profName])) {
                    $parts = explode(' ', $profName, 2);
                    $nom = $parts[0];
                    $pat = $parts[1] ?? 'COE';
                    $instIndex++;
                    $instPersonaId = DB::table('personas')->insertGetId([
                        'ci' => 'INST-' . $instIndex,
                        'nombres' => $nom,
                        'paterno' => $pat,
                        'tipo_persona' => 'INSTRUCTOR',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $newInstId = DB::table('instructores')->insertGetId([
                        'persona_id' => $instPersonaId,
                        'especialidad' => $discName,
                        'estado' => 'ACTIVO',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $instructoresMap[$profName] = $newInstId;
                }
                $instructorId = $instructoresMap[$profName];

                // Modalidad
                $modName = strtoupper($item['modalidad']);
                if ($modName === '') $modName = 'MENSUAL';
                $modKey = $discId . '-' . $modName;
                if (!isset($modalidadesMap[$modKey])) {
                    $clases = $this->parseClases($modName);
                    $newModId = DB::table('modalidades')->insertGetId([
                        'disciplina_id' => $discId,
                        'nombre' => $modName,
                        'cantidad_clases' => $clases,
                        'frecuencia_semanal' => $this->parseFrecuencia($modName),
                        'activo' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $modalidadesMap[$modKey] = $newModId;
                }
                $modalidadId = $modalidadesMap[$modKey];

                // Tarifa (una por modalidad para PARTICULAR)
                $costo = $item['costo'] > 0 ? $item['costo'] : 200.00;
                if (!isset($tarifasMap[$modalidadId])) {
                    $newTarifaId = DB::table('tarifas')->insertGetId([
                        'modalidad_id' => $modalidadId,
                        'tipo_persona' => 'PARTICULAR',
                        'precio' => $costo,
                        'activo' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $tarifasMap[$modalidadId] = $newTarifaId;
                    $tarifasPrecioMap[$modalidadId] = $costo;
                } else {
                    // Si el costo actual es mayor (precio regular sin descuento), actualizar precio base
                    if ($costo > ($tarifasPrecioMap[$modalidadId] ?? 0)) {
                        DB::table('tarifas')->where('id', $tarifasMap[$modalidadId])->update(['precio' => $costo]);
                        $tarifasPrecioMap[$modalidadId] = $costo;
                    }
                }

                // Horario
                $times = $this->parseTimes($item['horario']);
                $horarioKey = $discId . '-' . $instructorId . '-' . substr($times['inicio'], 0, 5);
                if (!isset($horariosMap[$horarioKey])) {
                    $capacidad = $capacidadesPorDisciplina[$discName] ?? 20;
                    $newHorarioId = DB::table('horarios_cursos')->insertGetId([
                        'disciplina_id' => $discId,
                        'modalidad_id' => $modalidadId,
                        'instructor_id' => $instructorId,
                        'dias' => $this->parseDias($item['horario'], $item['modalidad']),
                        'hora_inicio' => $times['inicio'],
                        'hora_fin' => $times['fin'],
                        'capacidad_maxima' => $capacidad,
                        'activo' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $horariosMap[$horarioKey] = $newHorarioId;
                }
            }

            // 6. Alumnos
            $this->info("Importando alumnos y membresías particulares...");
            $personasMap = [];
            $existingPersonas = DB::table('personas')->get();
            foreach ($existingPersonas as $ep) {
                $cleanName = strtoupper(preg_replace('/\s+/', ' ', trim($ep->nombres)));
                $cleanAp = strtoupper(preg_replace('/\s+/', ' ', trim($ep->paterno . ' ' . $ep->materno)));
                $key = "$cleanName|$cleanAp";
                $personasMap[$key] = $ep->id;
            }

            $maxCi = DB::table('personas')->where('tipo_persona', 'PARTICULAR')->max('ci');
            $studentSeq = ($maxCi && is_numeric($maxCi)) ? (int)$maxCi : 1400000;
            $partSeq = DB::table('particulares')->count();

            // 7. Insertar Inscripciones, Facturas y Tarjetas
            $this->info("Insertando inscripciones, facturas y tarjetas para " . count($rows) . " registros...");
            $totalInscripciones = 0;
            $totalPagos = 0;
            $totalMonto = 0.0;

            $cajeroId = DB::table('users')->where('email', 'cajero@siscoe.bo')->value('id') ?? 1;

            $maxInsId = DB::table('inscripciones')->max('id') ?? 0;
            $facturaSeq = 10000;

            foreach ($rows as $idx => $item) {
                $discName = $item['disciplina'];
                $discId = $disciplinasMap[$discName];
                $profName = strtoupper($item['profesor']);
                if ($profName === '' || $profName === 'SIN INSTRUCTOR') $profName = 'INSTRUCTOR GENERAL';
                $instructorId = $instructoresMap[$profName];
                $modName = strtoupper($item['modalidad'] ?: 'MENSUAL');
                $modalidadId = $modalidadesMap[$discId . '-' . $modName];
                $tarifaId = $tarifasMap[$modalidadId];
                $costo = $item['costo'] > 0 ? $item['costo'] : 200.00;
                $precioBaseTarifa = $tarifasPrecioMap[$modalidadId] ?? $costo;

                $times = $this->parseTimes($item['horario']);
                $horarioKey = $discId . '-' . $instructorId . '-' . substr($times['inicio'], 0, 5);
                $horarioCursoId = $horariosMap[$horarioKey];

                // Persona
                $nombres = strtoupper(preg_replace('/\s+/', ' ', trim($item['nombre'])));
                $apellidos = strtoupper(preg_replace('/\s+/', ' ', trim($item['apellido'])));
                $studentKey = "$nombres|$apellidos";

                if (!isset($personasMap[$studentKey])) {
                    $studentSeq++;
                    $partSeq++;
                    $apParts = explode(' ', $apellidos, 2);
                    $pat = $apParts[0];
                    $mat = $apParts[1] ?? null;

                    $tel = null;
                    if (preg_match('/[0-9]{7,8}/', $item['observacion'], $matchTel)) {
                        $tel = $matchTel[0];
                    }

                    $personaId = DB::table('personas')->insertGetId([
                        'ci' => (string)$studentSeq,
                        'nombres' => $nombres,
                        'paterno' => $pat,
                        'materno' => $mat,
                        'telf_cel' => $tel,
                        'tipo_persona' => 'PARTICULAR',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    DB::table('particulares')->insert([
                        'persona_id' => $personaId,
                        'codigo_particular' => 'PART-' . str_pad($partSeq, 5, '0', STR_PAD_LEFT),
                        'estado' => 'ACTIVO',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $personasMap[$studentKey] = $personaId;
                }
                $personaId = $personasMap[$studentKey];

                $fInicio = $item['fecha_inicio'] ?? '2026-03-01';
                $fFin = $item['fecha_fin'] ?? date('Y-m-d', strtotime("$fInicio +30 days"));
                $fIns = $item['fecha_ins'] ?? $fInicio;

                // Estado de inscripción: ACTIVA si vence después de marzo 2026
                $estado = (strtotime($fFin) >= strtotime('2026-03-15')) ? 'ACTIVA' : 'FINALIZADA';

                $currentNum = $maxInsId + $idx + 1;
                $codigoInscripcion = 'INS-2026-' . str_pad($currentNum, 6, '0', STR_PAD_LEFT);

                $inscripcionId = DB::table('inscripciones')->insertGetId([
                    'codigo_inscripcion' => $codigoInscripcion,
                    'persona_id' => $personaId,
                    'horario_curso_id' => $horarioCursoId,
                    'tarifa_id' => $tarifaId,
                    'fecha_inscripcion' => $fIns,
                    'fecha_inicio' => $fInicio,
                    'fecha_fin' => $fFin,
                    'estado' => $estado,
                    'usuario_creador_id' => $cajeroId,
                    'created_at' => $fIns . ' 08:30:00',
                    'updated_at' => now(),
                ]);
                $totalInscripciones++;

                // Pago / Factura
                $numFactura = $item['factura'];
                if ($numFactura === '') {
                    $facturaSeq++;
                    $numFactura = 'REC-' . $facturaSeq;
                }

                $descuentoPorc = 0;
                $montoDesc = 0;
                $importeBase = max($costo, $precioBaseTarifa);
                $tipoBeneficio = 'NINGUNO';
                $motivoDesc = null;

                if (stripos($item['observacion'], 'DESC') !== false) {
                    $tipoBeneficio = 'DESCUENTO INSTITUCIONAL';
                    $motivoDesc = $item['observacion'];
                    if (preg_match('/([0-9]{1,2})\s*%/', $item['observacion'], $mDesc)) {
                        $descuentoPorc = (float)$mDesc[1];
                    } elseif ($importeBase > $costo) {
                        $descuentoPorc = round((($importeBase - $costo) / $importeBase) * 100, 1);
                    }
                    $montoDesc = round($importeBase - $costo, 2);
                } elseif ($importeBase > $costo) {
                    $montoDesc = round($importeBase - $costo, 2);
                    $descuentoPorc = round(($montoDesc / $importeBase) * 100, 1);
                    $tipoBeneficio = 'TARIFA PREFERENCIAL';
                    $motivoDesc = $item['observacion'] ?: 'Tarifa promocional';
                }

                DB::table('pagos_facturas')->insert([
                    'inscripcion_id' => $inscripcionId,
                    'numero_factura' => $numFactura,
                    'importe_base' => $importeBase,
                    'porcentaje_descuento' => $descuentoPorc,
                    'monto_descuento' => $montoDesc,
                    'importe_final' => $costo,
                    'tipo_beneficio' => $tipoBeneficio,
                    'motivo_descuento' => $motivoDesc,
                    'autorizacion_descuento' => $motivoDesc ? 'ADMINISTRACION COE' : null,
                    'fecha_pago' => $fIns . ' 08:35:00',
                    'cajero_id' => $cajeroId,
                    'created_at' => $fIns . ' 08:35:00',
                    'updated_at' => now(),
                ]);
                $totalPagos++;
                $totalMonto += $costo;

                // Tarjeta
                $codigoTarjeta = 'CRD-2026-' . str_pad($currentNum, 6, '0', STR_PAD_LEFT);
                $clasesPermitidas = $this->parseClases($modName);
                $asistenciasUsadas = ($estado === 'ACTIVA') ? rand(0, min(3, $clasesPermitidas)) : $clasesPermitidas;

                DB::table('tarjetas_inscripcion')->insert([
                    'inscripcion_id' => $inscripcionId,
                    'codigo_tarjeta' => $codigoTarjeta,
                    'codigo_qr' => 'COE-QR-' . $codigoTarjeta . '-' . $numFactura,
                    'fecha_emision' => $fIns,
                    'fecha_vencimiento' => $fFin,
                    'asistencias_permitidas' => $clasesPermitidas,
                    'asistencias_utilizadas' => $asistenciasUsadas,
                    'estado' => ($estado === 'ACTIVA' ? 'HABILITADA' : 'VENCIDA'),
                    'reimpresiones_count' => 0,
                    'created_at' => $fIns . ' 08:36:00',
                    'updated_at' => now(),
                ]);

                if (($idx + 1) % 500 === 0) {
                    $this->line("Procesadas " . ($idx + 1) . " / " . count($rows) . " inscripciones...");
                }
            }

            DB::commit();

            $this->info("==================================================");
            $this->info("¡IMPORTACIÓN COMPLETADA CON ÉXITO!");
            $this->info("Total Alumnos en Padrón: " . count($personasMap));
            $this->info("Total Nuevas Inscripciones: $totalInscripciones");
            $this->info("Total Facturas/Recibos Generados: $totalPagos");
            $this->info("Recaudación Total Histórica: Bs. " . number_format($totalMonto, 2));
            $this->info("==================================================");

            return 0;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error durante la importación: " . $e->getMessage());
            $this->error($e->getTraceAsString());
            return 1;
        }
    }

    private function normalizeDisciplina(string $name): string
    {
        $name = strtoupper(trim($name));
        $map = [
            'BAILE FITNNES' => 'BAILE FITNESS',
            'INST. MUSICALES' => 'INSTRUMENTOS MUSICALES',
        ];
        return $map[$name] ?? $name;
    }

    private function excelDate($val): ?string
    {
        if (!$val || !is_numeric($val)) return null;
        $v = (float)$val;
        if ($v < 30000 || $v > 65000) return null;
        $unix = ($v - 25569) * 86400;
        return gmdate('Y-m-d', $unix);
    }

    private function parseClases(string $modalidad): int
    {
        if (preg_match('/([0-9]{1,2})\s*(CLASES|SESIONES)/i', $modalidad, $m)) {
            return (int)$m[1];
        }
        if (stripos($modalidad, 'MENSUAL') !== false) return 20;
        if (stripos($modalidad, '1/2 MES') !== false) return 10;
        if (stripos($modalidad, '2 X SEMANA') !== false) return 8;
        if (stripos($modalidad, '3XSEMANA') !== false) return 12;
        return 8;
    }

    private function parseFrecuencia(string $modalidad): string
    {
        if (stripos($modalidad, 'SAB') !== false) return 'SABADOS';
        if (stripos($modalidad, 'MI&VI') !== false) return 'MIERCOLES Y VIERNES';
        if (stripos($modalidad, 'MA&JU') !== false) return 'MARTES Y JUEVES';
        if (stripos($modalidad, 'MA&VI') !== false) return 'MARTES Y VIERNES';
        if (stripos($modalidad, 'JU&VI') !== false) return 'JUEVES Y VIERNES';
        if (stripos($modalidad, 'JU&SA') !== false) return 'JUEVES Y SABADOS';
        if (stripos($modalidad, 'MENSUAL') !== false) return 'LUNES A VIERNES';
        return 'LUN-MIE-VIE';
    }

    private function parseTimes(string $horario): array
    {
        if (preg_match('/([0-9]{1,2}:[0-9]{2})\s*-\s*([0-9]{1,2}:[0-9]{2})/', $horario, $m)) {
            $h1 = str_pad($m[1], 5, '0', STR_PAD_LEFT) . ':00';
            $h2 = str_pad($m[2], 5, '0', STR_PAD_LEFT) . ':00';
            return ['inicio' => $h1, 'fin' => $h2];
        }
        return ['inicio' => '08:00:00', 'fin' => '09:00:00'];
    }

    private function parseDias(string $horario, string $modalidad): string
    {
        if (stripos($horario, 'LUN A JUE') !== false) return 'LUNES A JUEVES';
        if (stripos($modalidad, 'SAB') !== false) return 'SABADOS';
        if (stripos($modalidad, 'MI&VI') !== false) return 'MIERCOLES Y VIERNES';
        if (stripos($modalidad, 'MA&JU') !== false) return 'MARTES Y JUEVES';
        if (stripos($modalidad, 'MENSUAL') !== false) return 'LUNES A VIERNES';
        return 'LUNES A VIERNES';
    }
}

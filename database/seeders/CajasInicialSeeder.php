<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CajasInicialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Grados Militares
        $grados = [
            ['nombre' => 'GENERAL DE EJERCITO', 'abreviatura' => 'GRAL. EJTO.'],
            ['nombre' => 'GENERAL DE DIVISION', 'abreviatura' => 'GRAL. DIV.'],
            ['nombre' => 'GENERAL DE BRIGADA', 'abreviatura' => 'GRAL. BRIG.'],
            ['nombre' => 'CORONEL', 'abreviatura' => 'CNL.'],
            ['nombre' => 'TENIENTE CORONEL', 'abreviatura' => 'TCNL.'],
            ['nombre' => 'MAYOR', 'abreviatura' => 'MY.'],
            ['nombre' => 'CAPITAN', 'abreviatura' => 'CAP.'],
            ['nombre' => 'TENIENTE', 'abreviatura' => 'TTE.'],
            ['nombre' => 'SUBTENIENTE', 'abreviatura' => 'SUBTTE.'],
            ['nombre' => 'SUBOFICIAL MAESTRE', 'abreviatura' => 'SOF. MTRE.'],
            ['nombre' => 'SUBOFICIAL MAYOR', 'abreviatura' => 'SOF. MY.'],
            ['nombre' => 'SUBOFICIAL PRIMERO', 'abreviatura' => 'SOF. 1RO.'],
            ['nombre' => 'SUBOFICIAL SEGUNDO', 'abreviatura' => 'SOF. 2DO.'],
            ['nombre' => 'SUBOFICIAL INICIAL', 'abreviatura' => 'SOF. INCL.'],
            ['nombre' => 'SARGENTO PRIMERO', 'abreviatura' => 'SGTO. 1RO.'],
            ['nombre' => 'SARGENTO SEGUNDO', 'abreviatura' => 'SGTO. 2DO.'],
            ['nombre' => 'SARGENTO INICIAL', 'abreviatura' => 'SGTO. INCL.'],
        ];
        foreach ($grados as $g) {
            DB::table('grados')->updateOrInsert(['nombre' => $g['nombre']], $g);
        }

        // 2. Armas y Especialidades
        $armas = [
            'INFANTERIA', 'CABALLERIA', 'ARTILLERIA', 'INGENIERIA', 'COMUNICACIONES',
            'LOGISTICA', 'SANIDAD', 'MUSICA', 'INTENDENCIA', 'MATERIAL BELICO'
        ];
        foreach ($armas as $a) {
            DB::table('armas_especialidades')->updateOrInsert(['nombre' => $a], ['nombre' => $a]);
        }

        // 3. Parentescos
        $parentescos = ['ESPOSO/A', 'HIJO/A', 'PADRE', 'MADRE', 'HERMANO/A', 'TUTOR/A'];
        foreach ($parentescos as $p) {
            DB::table('parentescos')->updateOrInsert(['nombre' => $p], ['nombre' => $p]);
        }

        // 4. Disciplinas, Modalidades y Tarifas base (extraídas del Excel real)
        $disciplinasData = [
            'NATACION' => [
                ['modalidad' => '16 CLASES', 'clases' => 16, 'frecuencia' => 'LUNES A VIERNES', 'socio' => 80.00, 'particular' => 380.00],
                ['modalidad' => '8 CLASES MA&JU', 'clases' => 8, 'frecuencia' => 'MARTES Y JUEVES', 'socio' => 80.00, 'particular' => 200.00],
                ['modalidad' => '8 CLASES MI&VI', 'clases' => 8, 'frecuencia' => 'MIERCOLES Y VIERNES', 'socio' => 80.00, 'particular' => 200.00],
                ['modalidad' => '4 CLASES SAB', 'clases' => 4, 'frecuencia' => 'SABADOS', 'socio' => 55.00, 'particular' => 110.00],
            ],
            'NATACION NIÑOS' => [
                ['modalidad' => '16 CLASES', 'clases' => 16, 'frecuencia' => 'LUNES A VIERNES', 'socio' => 80.00, 'particular' => 380.00],
                ['modalidad' => '8 CLASES MA&JU', 'clases' => 8, 'frecuencia' => 'MARTES Y JUEVES', 'socio' => 80.00, 'particular' => 200.00],
                ['modalidad' => '8 CLASES MI&VI', 'clases' => 8, 'frecuencia' => 'MIERCOLES Y VIERNES', 'socio' => 80.00, 'particular' => 200.00],
                ['modalidad' => '4 CLASES SAB', 'clases' => 4, 'frecuencia' => 'SABADOS', 'socio' => 55.00, 'particular' => 110.00],
            ],
            'TENIS' => [
                ['modalidad' => 'MENSUAL COMPLETO', 'clases' => 20, 'frecuencia' => 'LUNES A VIERNES', 'socio' => 80.00, 'particular' => 240.00],
                ['modalidad' => '2 X SEMANA', 'clases' => 8, 'frecuencia' => '2 DIAS SEMANA', 'socio' => 80.00, 'particular' => 130.00],
                ['modalidad' => '3 X SEMANA', 'clases' => 12, 'frecuencia' => '3 DIAS SEMANA', 'socio' => 80.00, 'particular' => 180.00],
            ],
            'TAEKWONDO' => [
                ['modalidad' => 'MENSUAL', 'clases' => 12, 'frecuencia' => '3 VECES POR SEMANA', 'socio' => 80.00, 'particular' => 180.00],
            ],
            'GIMNASIO' => [
                ['modalidad' => 'MENSUAL LIBRE', 'clases' => 24, 'frecuencia' => 'LUNES A SABADO', 'socio' => 50.00, 'particular' => 150.00],
            ],
            'BASQUETBALL' => [
                ['modalidad' => 'MENSUAL', 'clases' => 12, 'frecuencia' => '3 VECES POR SEMANA', 'socio' => 80.00, 'particular' => 200.00],
            ],
            'FUTSAL' => [
                ['modalidad' => 'MENSUAL', 'clases' => 12, 'frecuencia' => '3 VECES POR SEMANA', 'socio' => 80.00, 'particular' => 180.00],
            ],
            'VOLEIBOL' => [
                ['modalidad' => 'MENSUAL', 'clases' => 12, 'frecuencia' => '3 VECES POR SEMANA', 'socio' => 80.00, 'particular' => 180.00],
            ],
            'BAILE FITNESS' => [
                ['modalidad' => 'MENSUAL', 'clases' => 12, 'frecuencia' => '3 VECES POR SEMANA', 'socio' => 80.00, 'particular' => 160.00],
            ],
        ];

        foreach ($disciplinasData as $discNombre => $modalidades) {
            $discId = DB::table('disciplinas')->insertGetId([
                'nombre' => $discNombre,
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            foreach ($modalidades as $mod) {
                $modId = DB::table('modalidades')->insertGetId([
                    'disciplina_id' => $discId,
                    'nombre' => $mod['modalidad'],
                    'cantidad_clases' => $mod['clases'],
                    'frecuencia_semanal' => $mod['frecuencia'],
                    'activo' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // Tarifa Socio
                DB::table('tarifas')->insert([
                    'modalidad_id' => $modId,
                    'tipo_persona' => 'SOCIO',
                    'precio' => $mod['socio'],
                    'activo' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // Tarifa Beneficiario (mismo precio de socio o diferenciado)
                DB::table('tarifas')->insert([
                    'modalidad_id' => $modId,
                    'tipo_persona' => 'BENEFICIARIO',
                    'precio' => $mod['socio'],
                    'activo' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // Tarifa Particular
                DB::table('tarifas')->insert([
                    'modalidad_id' => $modId,
                    'tipo_persona' => 'PARTICULAR',
                    'precio' => $mod['particular'],
                    'activo' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        // 5. Instructores Base (del Excel real)
        $instructoresNombres = [
            ['nombres' => 'ROSALIA', 'paterno' => 'PEREZ', 'especialidad' => 'NATACION'],
            ['nombres' => 'MICAELA', 'paterno' => 'MUÑOZ', 'especialidad' => 'NATACION'],
            ['nombres' => 'MARIA', 'paterno' => 'PABON', 'especialidad' => 'NATACION'],
            ['nombres' => 'ROBERTO', 'paterno' => 'LEON', 'especialidad' => 'NATACION'],
            ['nombres' => 'PAMELA', 'paterno' => 'PABON', 'especialidad' => 'NATACION'],
            ['nombres' => 'EDUARDO', 'paterno' => 'LANZA', 'especialidad' => 'NATACION NIÑOS'],
            ['nombres' => 'VERONICA', 'paterno' => 'FERRUFINO', 'especialidad' => 'NATACION'],
            ['nombres' => 'ANDREA', 'paterno' => 'RODRIGUEZ', 'especialidad' => 'BASQUETBALL'],
            ['nombres' => 'EMILIO', 'paterno' => 'DE LA FUENTE', 'especialidad' => 'TENIS'],
        ];

        foreach ($instructoresNombres as $inst) {
            $personaId = DB::table('personas')->insertGetId([
                'nombres' => $inst['nombres'],
                'paterno' => $inst['paterno'],
                'tipo_persona' => 'INSTRUCTOR',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::table('instructores')->insert([
                'persona_id' => $personaId,
                'especialidad' => $inst['especialidad'],
                'estado' => 'ACTIVO',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}


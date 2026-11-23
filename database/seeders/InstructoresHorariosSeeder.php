<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InstructoresHorariosSeeder extends Seeder
{
    public function run(): void
    {
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
            ['nombres' => 'CARLOS', 'paterno' => 'QUISPE', 'especialidad' => 'TAEKWONDO'],
            ['nombres' => 'MARCELO', 'paterno' => 'GUTIERREZ', 'especialidad' => 'GIMNASIO'],
            ['nombres' => 'JAVIER', 'paterno' => 'FLORES', 'especialidad' => 'FUTSAL'],
            ['nombres' => 'CLAUDIA', 'paterno' => 'TORREZ', 'especialidad' => 'VOLEIBOL'],
            ['nombres' => 'PAOLA', 'paterno' => 'SOLIZ', 'especialidad' => 'BAILE FITNESS'],
        ];

        $instructoresMap = [];
        foreach ($instructoresNombres as $inst) {
            $persona = DB::table('personas')->where('paterno', $inst['paterno'])->where('nombres', $inst['nombres'])->first();
            if (!$persona) {
                $personaId = DB::table('personas')->insertGetId([
                    'nombres' => $inst['nombres'],
                    'paterno' => $inst['paterno'],
                    'tipo_persona' => 'INSTRUCTOR',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                $personaId = $persona->id;
            }

            $instructor = DB::table('instructores')->where('persona_id', $personaId)->first();
            if (!$instructor) {
                $instructorId = DB::table('instructores')->insertGetId([
                    'persona_id' => $personaId,
                    'especialidad' => $inst['especialidad'],
                    'estado' => 'ACTIVO',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                $instructorId = $instructor->id;
            }

            $instructoresMap[$inst['paterno']] = $instructorId;
        }

        // Obtener disciplinas
        $disciplinas = DB::table('disciplinas')->pluck('id', 'nombre');

        $horariosData = [
            // NATACION
            [
                'disciplina' => 'NATACION',
                'instructor' => 'LEON',
                'dias' => 'LUNES A VIERNES',
                'hora_inicio' => '15:00',
                'hora_fin' => '16:00',
                'capacidad' => 20
            ],
            [
                'disciplina' => 'NATACION',
                'instructor' => 'MUÑOZ',
                'dias' => 'LUNES A VIERNES',
                'hora_inicio' => '19:00',
                'hora_fin' => '20:00',
                'capacidad' => 20
            ],
            [
                'disciplina' => 'NATACION',
                'instructor' => 'PEREZ',
                'dias' => 'LUNES A VIERNES',
                'hora_inicio' => '07:00',
                'hora_fin' => '08:00',
                'capacidad' => 15
            ],
            [
                'disciplina' => 'NATACION',
                'instructor' => 'FERRUFINO',
                'dias' => 'LUNES A VIERNES',
                'hora_inicio' => '16:00',
                'hora_fin' => '17:00',
                'capacidad' => 20
            ],
            [
                'disciplina' => 'NATACION',
                'instructor' => 'PABON',
                'dias' => 'SABADOS',
                'hora_inicio' => '09:00',
                'hora_fin' => '11:00',
                'capacidad' => 25
            ],

            // NATACION NIÑOS
            [
                'disciplina' => 'NATACION NIÑOS',
                'instructor' => 'LANZA',
                'dias' => 'LUNES A VIERNES',
                'hora_inicio' => '15:00',
                'hora_fin' => '16:00',
                'capacidad' => 15
            ],
            [
                'disciplina' => 'NATACION NIÑOS',
                'instructor' => 'LANZA',
                'dias' => 'LUNES A VIERNES',
                'hora_inicio' => '16:00',
                'hora_fin' => '17:00',
                'capacidad' => 15
            ],
            [
                'disciplina' => 'NATACION NIÑOS',
                'instructor' => 'LANZA',
                'dias' => 'SABADOS',
                'hora_inicio' => '10:00',
                'hora_fin' => '12:00',
                'capacidad' => 15
            ],

            // TENIS
            [
                'disciplina' => 'TENIS',
                'instructor' => 'DE LA FUENTE',
                'dias' => 'LUNES A VIERNES',
                'hora_inicio' => '08:00',
                'hora_fin' => '09:30',
                'capacidad' => 8
            ],
            [
                'disciplina' => 'TENIS',
                'instructor' => 'DE LA FUENTE',
                'dias' => 'LUNES, MIERCOLES Y VIERNES',
                'hora_inicio' => '16:00',
                'hora_fin' => '17:30',
                'capacidad' => 8
            ],

            // TAEKWONDO
            [
                'disciplina' => 'TAEKWONDO',
                'instructor' => 'QUISPE',
                'dias' => 'LUNES, MIERCOLES Y VIERNES',
                'hora_inicio' => '17:00',
                'hora_fin' => '18:30',
                'capacidad' => 20
            ],
            [
                'disciplina' => 'TAEKWONDO',
                'instructor' => 'QUISPE',
                'dias' => 'LUNES, MIERCOLES Y VIERNES',
                'hora_inicio' => '18:30',
                'hora_fin' => '20:00',
                'capacidad' => 20
            ],

            // GIMNASIO
            [
                'disciplina' => 'GIMNASIO',
                'instructor' => 'GUTIERREZ',
                'dias' => 'LUNES A SABADO (TURNO MAÑANA)',
                'hora_inicio' => '06:00',
                'hora_fin' => '12:00',
                'capacidad' => 50
            ],
            [
                'disciplina' => 'GIMNASIO',
                'instructor' => 'GUTIERREZ',
                'dias' => 'LUNES A SABADO (TURNO TARDE-NOCHE)',
                'hora_inicio' => '14:00',
                'hora_fin' => '21:00',
                'capacidad' => 50
            ],

            // BASQUETBALL
            [
                'disciplina' => 'BASQUETBALL',
                'instructor' => 'RODRIGUEZ',
                'dias' => 'LUNES, MIERCOLES Y VIERNES',
                'hora_inicio' => '16:00',
                'hora_fin' => '17:30',
                'capacidad' => 25
            ],

            // FUTSAL
            [
                'disciplina' => 'FUTSAL',
                'instructor' => 'FLORES',
                'dias' => 'MARTES Y JUEVES',
                'hora_inicio' => '18:00',
                'hora_fin' => '19:30',
                'capacidad' => 20
            ],

            // VOLEIBOL
            [
                'disciplina' => 'VOLEIBOL',
                'instructor' => 'TORREZ',
                'dias' => 'MARTES Y JUEVES',
                'hora_inicio' => '17:00',
                'hora_fin' => '18:30',
                'capacidad' => 20
            ],

            // BAILE FITNESS
            [
                'disciplina' => 'BAILE FITNESS',
                'instructor' => 'SOLIZ',
                'dias' => 'LUNES, MIERCOLES Y VIERNES',
                'hora_inicio' => '08:30',
                'hora_fin' => '09:30',
                'capacidad' => 25
            ],
            [
                'disciplina' => 'BAILE FITNESS',
                'instructor' => 'SOLIZ',
                'dias' => 'LUNES, MIERCOLES Y VIERNES',
                'hora_inicio' => '19:00',
                'hora_fin' => '20:00',
                'capacidad' => 25
            ],
        ];

        foreach ($horariosData as $hd) {
            $disciplinaId = $disciplinas[$hd['disciplina']] ?? null;
            $instructorId = $instructoresMap[$hd['instructor']] ?? null;

            if ($disciplinaId && $instructorId) {
                $existe = DB::table('horarios_cursos')
                    ->where('disciplina_id', $disciplinaId)
                    ->where('instructor_id', $instructorId)
                    ->where('hora_inicio', 'like', $hd['hora_inicio'] . '%')
                    ->first();

                if (!$existe) {
                    DB::table('horarios_cursos')->insert([
                        'disciplina_id' => $disciplinaId,
                        'instructor_id' => $instructorId,
                        'dias' => $hd['dias'],
                        'hora_inicio' => $hd['hora_inicio'],
                        'hora_fin' => $hd['hora_fin'],
                        'capacidad_maxima' => $hd['capacidad'],
                        'activo' => true,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
        }
    }
}

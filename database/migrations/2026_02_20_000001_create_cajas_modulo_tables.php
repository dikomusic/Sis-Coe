<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Catálogos Normalizados (3FN)
        Schema::create('grados', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('abreviatura', 30)->nullable();
            $table->timestamps();
        });

        Schema::create('armas_especialidades', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->timestamps();
        });

        Schema::create('destinos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->timestamps();
        });

        Schema::create('parentescos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->timestamps();
        });

        // 2. Tabla Central de PERSONAS (Cero redundancia)
        Schema::create('personas', function (Blueprint $table) {
            $table->id();
            $table->string('ci', 50)->nullable()->index();
            $table->string('complemento', 10)->nullable();
            $table->string('nombres', 150);
            $table->string('paterno', 100)->nullable();
            $table->string('materno', 100)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('genero', 10)->nullable();
            $table->string('tipo_sangre', 10)->nullable();
            $table->string('telf_cel', 50)->nullable();
            $table->string('direccion', 255)->nullable();
            $table->string('foto', 255)->nullable();
            $table->string('tipo_persona', 30)->default('PARTICULAR'); // SOCIO, BENEFICIARIO, PARTICULAR, INSTRUCTOR
            $table->timestamps();
        });

        // 3. Vinculación normalizada para socios_titulares y beneficiarios existentes
        Schema::table('socio_titular', function (Blueprint $table) {
            $table->foreignId('persona_id')->nullable()->constrained('personas')->nullOnDelete();
            $table->foreignId('grado_id')->nullable()->constrained('grados')->nullOnDelete();
            $table->foreignId('arma_id')->nullable()->constrained('armas_especialidades')->nullOnDelete();
            $table->foreignId('destino_id')->nullable()->constrained('destinos')->nullOnDelete();
        });

        Schema::table('beneficiario_titular', function (Blueprint $table) {
            $table->foreignId('persona_id')->nullable()->constrained('personas')->nullOnDelete();
            $table->foreignId('parentesco_id')->nullable()->constrained('parentescos')->nullOnDelete();
        });

        // 4. Subtipo PARTICULARES (Para clientes externos en Cajas)
        Schema::create('particulares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('persona_id')->unique()->constrained('personas')->cascadeOnDelete();
            $table->string('codigo_particular', 50)->unique(); // RF-CAJ-05
            $table->string('estado', 30)->default('ACTIVO');
            $table->timestamps();
        });

        // 5. Subtipo INSTRUCTORES (Profesores de disciplinas)
        Schema::create('instructores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('persona_id')->unique()->constrained('personas')->cascadeOnDelete();
            $table->string('especialidad', 150)->nullable();
            $table->string('estado', 30)->default('ACTIVO');
            $table->timestamps();
        });

        // 6. Disciplinas (Actividades deportivas)
        Schema::create('disciplinas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique();
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        // 7. Modalidades por Disciplina (Frecuencia, cantidad de clases)
        Schema::create('modalidades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disciplina_id')->constrained('disciplinas')->cascadeOnDelete();
            $table->string('nombre', 100); // 16 CLASES, 8 CLASES MA&JU, MENSUAL, etc.
            $table->integer('cantidad_clases')->nullable();
            $table->string('frecuencia_semanal', 100)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        // 8. Tarifas Diferenciadas (RF-CAJ-12: Socio vs Beneficiario vs Particular)
        Schema::create('tarifas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modalidad_id')->constrained('modalidades')->cascadeOnDelete();
            $table->string('tipo_persona', 30); // 'SOCIO', 'BENEFICIARIO', 'PARTICULAR'
            $table->decimal('precio', 10, 2);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique(['modalidad_id', 'tipo_persona']);
        });

        // 9. Horarios Cursos e Instructores (RF-CAJ-14 al 21: Capacidad máxima y cupos)
        Schema::create('horarios_cursos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disciplina_id')->constrained('disciplinas')->cascadeOnDelete();
            $table->foreignId('modalidad_id')->nullable()->constrained('modalidades')->nullOnDelete();
            $table->foreignId('instructor_id')->constrained('instructores')->cascadeOnDelete();
            $table->string('dias', 100); // LUNES A VIERNES, SABADOS, etc.
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->integer('capacidad_maxima')->default(20);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        // 10. Inscripciones (RF-CAJ-22 al 31: Enlazadas directamente a personas)
        Schema::create('inscripciones', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_inscripcion', 50)->unique();
            $table->foreignId('persona_id')->constrained('personas')->restrictOnDelete();
            $table->foreignId('horario_curso_id')->constrained('horarios_cursos')->restrictOnDelete();
            $table->foreignId('tarifa_id')->constrained('tarifas')->restrictOnDelete();
            $table->date('fecha_inscripcion');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->string('estado', 30)->default('ACTIVA'); // ACTIVA, VENCIDA, FINALIZADA, REPROGRAMADA, CANCELADA
            $table->foreignId('usuario_creador_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 11. Pagos y Facturación (RF-CAJ-32 al 39: Descuentos, becas, nro factura)
        Schema::create('pagos_facturas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inscripcion_id')->unique()->constrained('inscripciones')->cascadeOnDelete();
            $table->string('numero_factura', 50);
            $table->decimal('importe_base', 10, 2);
            $table->decimal('porcentaje_descuento', 5, 2)->default(0);
            $table->decimal('monto_descuento', 10, 2)->default(0);
            $table->decimal('importe_final', 10, 2);
            $table->string('tipo_beneficio', 50)->default('NINGUNO'); // NINGUNO, DESCUENTO_AUTORIZADO, BECA_COMPLETA, MEDIA_BECA, CONVENIO
            $table->text('motivo_descuento')->nullable();
            $table->string('autorizacion_descuento', 150)->nullable();
            $table->timestamp('fecha_pago')->useCurrent();
            $table->foreignId('cajero_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 12. Tarjetas de Inscripción (RF-CAJ-40 al 46: Código QR, vigencia, impresión)
        Schema::create('tarjetas_inscripcion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inscripcion_id')->unique()->constrained('inscripciones')->cascadeOnDelete();
            $table->string('codigo_tarjeta', 100)->unique();
            $table->text('codigo_qr');
            $table->date('fecha_emision');
            $table->date('fecha_vencimiento');
            $table->integer('asistencias_permitidas')->default(0);
            $table->integer('asistencias_utilizadas')->default(0);
            $table->string('estado', 30)->default('HABILITADA'); // HABILITADA, VENCIDA, INHABILITADA
            $table->integer('reimpresiones_count')->default(0);
            $table->timestamp('ultima_reimpresion')->nullable();
            $table->timestamps();
        });

        // 13. Reprogramaciones (RF-CAJ-49 al 53: Hojas de ruta y autorizaciones)
        Schema::create('reprogramaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inscripcion_id')->constrained('inscripciones')->cascadeOnDelete();
            $table->date('fecha_anterior_vencimiento');
            $table->date('nueva_fecha_vencimiento');
            $table->integer('clases_reprogramadas')->default(0);
            $table->text('motivo');
            $table->string('hoja_ruta', 100);
            $table->string('autorizado_por', 150);
            $table->foreignId('usuario_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 14. Control de Asistencias por Tarjeta (RN-CAJ-12)
        Schema::create('asistencias_tarjeta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tarjeta_id')->constrained('tarjetas_inscripcion')->cascadeOnDelete();
            $table->timestamp('fecha_hora')->useCurrent();
            $table->foreignId('registrada_por')->nullable()->constrained('users')->nullOnDelete();
            $table->string('observacion', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistencias_tarjeta');
        Schema::dropIfExists('reprogramaciones');
        Schema::dropIfExists('tarjetas_inscripcion');
        Schema::dropIfExists('pagos_facturas');
        Schema::dropIfExists('inscripciones');
        Schema::dropIfExists('horarios_cursos');
        Schema::dropIfExists('tarifas');
        Schema::dropIfExists('modalidades');
        Schema::dropIfExists('disciplinas');
        Schema::dropIfExists('instructores');
        Schema::dropIfExists('particulares');

        Schema::table('beneficiario_titular', function (Blueprint $table) {
            $table->dropForeign(['parentesco_id']);
            $table->dropForeign(['persona_id']);
            $table->dropColumn(['persona_id', 'parentesco_id']);
        });

        Schema::table('socio_titular', function (Blueprint $table) {
            $table->dropForeign(['destino_id']);
            $table->dropForeign(['arma_id']);
            $table->dropForeign(['grado_id']);
            $table->dropForeign(['persona_id']);
            $table->dropColumn(['persona_id', 'grado_id', 'arma_id', 'destino_id']);
        });

        Schema::dropIfExists('personas');
        Schema::dropIfExists('parentescos');
        Schema::dropIfExists('destinos');
        Schema::dropIfExists('armas_especialidades');
        Schema::dropIfExists('grados');
    }
};

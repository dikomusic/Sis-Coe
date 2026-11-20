<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('credenciales', function (Blueprint $table) {
        $table->id();

        // RELACIÓN POLIMÓRFICA (socio o beneficiario)
        $table->morphs('entidad'); // crea entidad_id y entidad_type

        // Información de la credencial
        //$table->string('codigo')->nullable(); // código QR o de credencial
        $table->string('imagen_frontal')->nullable();
        $table->string('imagen_trasera')->nullable();

        // Fechas
        $table->date('fecha_emision');
        $table->date('fecha_vencimiento');

        // Estado de la credencial
        $table->enum('estado', [
            'activo',
            'inactivo',

        ])->default('activo');
        $table->enum('estado_credencial', [
            'AFILIACION',
            'RENOVACION_POR_PERDIDA',
            'RENOVACION_POR_CADUCIDAD',
            'BAJA_POR_PERDIDA',
            'BAJA_POR_MAYORIA_EDAD',
            'BAJA_POR_DIVORCIO',
            'BAJA_POR_CADUCIDAD',

        ])->default('AFILIACION');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credenciales');
    }
};

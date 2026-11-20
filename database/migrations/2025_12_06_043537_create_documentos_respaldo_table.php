<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documentos_respaldo', function (Blueprint $table) {
            $table->id();
            
            // Polimórfico perfecto
            $table->unsignedBigInteger('documentable_id');
            $table->string('documentable_type');
            
            // Tipo de documento (COSSMI, CI, etc.)
            $table->enum('tipo_documento', [
                'COSSMI',
                'CI_FRONTAL',
                'CI_POSTERIOR',
                'CERTIFICADO_NACIMIENTO',
                'CERTIFICADO_MATRIMONIO',
                'CERTIFICADO_DIVORCIO',
                'CERTIFICADO_DEFUNCION',
                'BOLETA_PAGO',
                'RESOLUCION_BAJA',
                'OTRO'
            ]);
            
            // Ruta del archivo
            $table->string('ruta_archivo');
            
            // Descripción opcional
            $table->string('descripcion')->nullable();
            
            // Quién subió (opcional)
            $table->unsignedBigInteger('creado_por')->nullable();
            
            $table->timestamps();

            // Índices para velocidad
            $table->index(['documentable_id', 'documentable_type']);
            $table->index('tipo_documento');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentos_respaldo');
    }
};
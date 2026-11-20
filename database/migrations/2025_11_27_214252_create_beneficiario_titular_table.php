<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('beneficiario_titular', function (Blueprint $table) {
            $table->id();

            // Llave foránea al titular
            $table->unsignedBigInteger('id_titular');

            $table->string('codigo_cossmil')->nullable();
            $table->string('parentesco');
            $table->string('paterno')->nullable();
            $table->string('materno')->nullable();
            $table->string('nombres');
            $table->string('ci')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('tipo_sangre')->nullable();
            $table->string('telf_cel')->nullable();
            $table->string('foto')->nullable();

            $table->date('fecha_alta_coe')->nullable();

            // Estado interno del beneficiario
            $table->string('estado_beneficiario')->default('ACTIVO');

            // Estado heredado del titular (nuevo campo)
            $table->string('estado_por_titular')->default('ACTIVO');

            $table->text('observacion')->nullable();
            $table->text('nota')->nullable();

            $table->timestamps();

            // Relación con cascada
            $table
                ->foreign('id_titular')
                ->references('id_titular')
                ->on('socio_titular')
                ->onDelete('cascade')   // Si se elimina el socio → se eliminan los beneficiarios
                ->onUpdate('cascade');  // Si cambia el id_titular → se actualiza
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beneficiario_titular');
    }
};

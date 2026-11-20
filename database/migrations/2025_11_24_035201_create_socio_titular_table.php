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
    Schema::create('socio_titular', function (Blueprint $table) {
        $table->id('id_titular');
        $table->string('boleta_pago', 50);
        $table->string('codigo_cossmil', 50);
        $table->string('grado', 100)->nullable();
        $table->string('arma_especialidad', 150)->nullable();
        $table->string('situacion_actual', 100)->nullable();
        $table->string('paterno', 100)->nullable();
        $table->string('materno', 100)->nullable();
        $table->string('nombres', 150)->nullable();
        $table->string('ci', 50)->nullable();
        $table->date('fecha_nacimiento')->nullable();
        $table->string('tipo_sangre', 10)->nullable();
        $table->string('direccion', 255)->nullable();
        $table->string('telf_cel', 50)->nullable();
        $table->string('foto', 255)->nullable();
        $table->string('destino', 150)->nullable();
        $table->date('fecha_alta_ejercito')->nullable();
        $table->date('fecha_alta_coe')->nullable();
        $table->string('estado_socio', 50)->nullable();
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('socio_titular');
}

};

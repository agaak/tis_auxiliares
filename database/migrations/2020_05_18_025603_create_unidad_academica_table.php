<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnidadAcademicaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unidad_academica', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->timestamps();
        });
        Schema::create('tipo_convocatoria', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombret_tipo');
            $table->timestamps();
        });

        Schema::create('convocatoria', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_unidad_academica')->unsigned();              
            $table->integer('id_tipo_convocatoria')->unsigned();
            $table->foreign('id_unidad_academica')->references('id')->on('unidad_academica')->onDelete('cascade');
            $table->foreign('id_tipo_convocatoria')->references('id')->on('tipo_convocatoria')->onDelete('cascade');
            $table->string('titulo',256)->nullable();
            $table->text('descripcion_convocatoria')->nullable();
            $table->date('fecha_inicio');
            $table->date('fecha_final');

            $table->timestamps();
        });
        Schema::create('requisito', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_convocatoria')->unsigned();
            $table->foreign('id_convocatoria')->references('id')->on('convocatoria')->onDelete('cascade');
            $table->text('descripcion');
            
            $table->timestamps();
        });
        Schema::create('evento', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_convocatoria');
            $table->foreign('id_convocatoria')->references('id')->on('convocatoria')->onDelete('cascade');
            $table->string('titulo_evento');
            $table->date('fecha_inicio');
            $table->date('fecha_final');
            $table->time('hora_inicio');
            $table->time('hora_final');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requisito');
        Schema::dropIfExists('evento');
        Schema::dropIfExists('convocatoria');  
        Schema::dropIfExists('tipo_convocatoria');
        Schema::dropIfExists('unidad_academica');
              
    }
}

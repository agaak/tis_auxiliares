<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalificacionFinalPostulanteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calf_final_postulante_merito', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_convocatoria');
            $table->foreign('id_convocatoria')->references('id')->on('convocatoria') ->onDelete('cascade');
            $table->integer('id_postulante');
            $table->foreign('id_postulante')->references('id')->on('postulante') ->onDelete('cascade');
            $table->integer('nota_final_merito');
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
        Schema::dropIfExists('calificacion_final_postulante');
    }
}
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitaTable extends Migration
{
    public function up()
    {
        Schema::create('cita', function (Blueprint $table) {
            $table->id('id_cita');
            $table->date('fecha');
            $table->time('hora');
            $table->foreignId('id_doctor')->constrained('doctor','id_doctor')->onDelete('cascade');
            $table->foreignId('id_paciente')->constrained('paciente','id_paciente')->onDelete('cascade');
            $table->string('motivo', 255)->nullable();
            $table->foreignId('id_estado')->constrained('estado_cita','id_estado')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cita');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePacienteTable extends Migration
{
    public function up()
    {
        Schema::create('paciente', function (Blueprint $table) {
            $table->id('id_paciente');
            $table->string('dni', 15)->nullable();
            $table->string('nombres', 150);
            $table->string('apellidos', 150)->nullable();
            $table->integer('edad')->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('direccion', 200)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('paciente');
    }
}

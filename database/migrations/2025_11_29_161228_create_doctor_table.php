<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorTable extends Migration
{
    public function up()
    {
        Schema::create('doctor', function (Blueprint $table) {
            $table->id('id_doctor');
            $table->string('nombre_doctor', 150);
            $table->string('telefono', 20)->nullable();
            $table->string('correo', 100)->nullable();

            // id_especialidad SIN NULL y con RESTRICT (no permite borrar especialidad si tiene doctores)
            $table->foreignId('id_especialidad')
                  ->constrained('especialidad', 'id_especialidad')
                  ->restrictOnDelete();

            // Relación con usuario
            $table->foreignId('id_usuario')
                  ->constrained('usuario', 'id_usuario')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('doctor');
    }
}

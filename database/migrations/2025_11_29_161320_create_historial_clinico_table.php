<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistorialClinicoTable extends Migration
{
    public function up()
    {
        Schema::create('historial_clinico', function (Blueprint $table) {
            $table->id('id_historial');
            $table->foreignId('id_cita')->constrained('cita','id_cita')->onDelete('cascade');
            $table->string('diagnostico', 255)->nullable();
            $table->string('tratamiento', 255)->nullable();
            $table->text('observaciones')->nullable();
            $table->dateTime('fecha_registro')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('historial_clinico');
    }
}

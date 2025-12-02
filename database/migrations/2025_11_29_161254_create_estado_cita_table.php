<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstadoCitaTable extends Migration
{
    public function up()
    {
        Schema::create('estado_cita', function (Blueprint $table) {
            $table->id('id_estado');
            $table->string('estado', 50);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('estado_cita');
    }
}

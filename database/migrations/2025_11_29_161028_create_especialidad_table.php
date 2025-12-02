<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEspecialidadTable extends Migration
{
    public function up()
    {
        Schema::create('especialidad', function (Blueprint $table) {
            $table->id('id_especialidad');
            $table->string('especialidad', 100);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('especialidad');
    }
}

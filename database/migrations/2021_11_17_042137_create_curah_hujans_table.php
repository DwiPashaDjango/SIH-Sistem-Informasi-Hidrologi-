<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurahHujansTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('curah_hujans', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->float('hujan_otomatis')->nullable();
            $table->float('hujan_biasa')->nullable();
            $table->text('keterangan')->nullable();
            $table->integer('pos_id')->unsigned();
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
        Schema::dropIfExists('curah_hujans');
    }
}

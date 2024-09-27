<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTMASTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_m_a_s', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->float('pagi')->nullable();
            $table->float('siang')->nullable();
            $table->float('sore')->nullable();
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
        Schema::dropIfExists('t_m_a_s');
    }
}

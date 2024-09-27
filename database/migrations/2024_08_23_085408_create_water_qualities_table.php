<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWaterQualitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('water_qualities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pos_id');
            $table->double('ph');
            $table->double('suhu');
            $table->double('zat');
            $table->double('orp');
            $table->double('conductivity');
            $table->double('resistivity');
            $table->double('oksigen');
            $table->double('cod');
            $table->double('khlorida');
            $table->double('nitrit');
            $table->double('nitrat');
            $table->double('sulfat');
            $table->double('phospat');
            $table->double('amonia');
            $table->double('tembaga');
            $table->double('mangan');
            $table->double('chrom');
            $table->double('total');
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
        Schema::dropIfExists('water_qualities');
    }
}

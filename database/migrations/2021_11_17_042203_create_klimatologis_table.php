<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKlimatologisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('klimatologis', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->float('termo_max_pagi')->nullable();
            $table->float('termo_max_siang')->nullable();
            $table->float('termo_max_sore')->nullable();
            $table->float('termo_min_pagi')->nullable();
            $table->float('termo_min_siang')->nullable();
            $table->float('termo_min_sore')->nullable();
            $table->float('bola_kering_pagi')->nullable();
            $table->float('bola_kering_siang')->nullable();
            $table->float('bola_kering_sore')->nullable();
            $table->float('bola_basah_pagi')->nullable();
            $table->float('bola_basah_siang')->nullable();
            $table->float('bola_basah_sore')->nullable();
            $table->float('rh')->nullable();
            $table->float('depresi')->nullable();
            $table->float('termo_apung_max')->nullable();
            $table->float('termo_apung_min')->nullable();
            $table->float('penguapan_plus')->nullable();
            $table->float('penguapan_min')->nullable();
            $table->float('anemometer_spedometer')->nullable();
            $table->float('anemometer_km')->nullable();
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
        Schema::dropIfExists('klimatologis');
    }
}

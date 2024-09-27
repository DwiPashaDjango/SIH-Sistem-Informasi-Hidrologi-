<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Klimatologi extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'termo_max_pagi',
        'termo_max_siang',
        'termo_max_sore',
        'termo_min_pagi',
        'termo_min_siang',
        'termo_min_sore',
        'bola_kering_pagi',
        'bola_kering_siang',
        'bola_kering_sore',
        'bola_basah_pagi',
        'bola_basah_siang',
        'bola_basah_sore',
        'rh',
        'termo_apung_max',
        'termo_apung_min',
        'penguapan_plus',
        'penguapan_min',
        'anemometer_spedometer',
        'hujan_otomatis',
        'hujan_biasa',
        'sinar_matahari',
        'keterangan',
        'pos_id'
    ];
    
    protected $appends = ['anemometerTomorrow'];
    
    public function getAnemometerTomorrowAttribute()
    {
        $tanggalSekarang = new Carbon($this->tanggal);
        $data = Klimatologi::where('pos_id', $this->pos_id)->whereDate('tanggal', $tanggalSekarang->addDays(1))->first();
        if(empty($data)) {
            return 0;
        }
        return $data->anemometer_spedometer;
    }

    public function pos() {
        return $this->belongsTo('App\Models\Post','pos_id', 'id');
    }
}

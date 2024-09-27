<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'koordinatx',
        'koordinaty',
        'kabupaten',
        'provinsi',
        'lokasi',
        'jenis_id',
        'gambar',
        'normal',
        'waspada',
        'siaga',
        'awas',
        'deleted_at',
        'provinces_id',
        'regencies_id',
        'subdas_id',
        'tma_banjir',
    ];

    public function jenis()
    {
        return $this->belongsTo('App\Models\Jenis', 'jenis_id', 'id');
    }

    public function user()
    {
        return $this->hasMany('App\Models\User', 'pos_id', 'id');
    }

    public function curah_hujan()
    {
        return $this->hasMany('App\Models\CurahHujan', 'pos_id', 'id');
    }

    public function tma()
    {
        return $this->hasMany('App\Models\TMA', 'pos_id', 'id');
    }

    public function klimatologi()
    {
        return $this->hasMany('App\Models\Klimatologi', 'pos_id', 'id');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'provinces_id', 'id');
    }

    public function regencie()
    {
        return $this->belongsTo(Regency::class, 'regencies_id', 'id');
    }

    public function subdas()
    {
        return $this->belongsTo(SubDas::class, 'subdas_id', 'id');
    }

    public function qualityWater()
    {
        return $this->belongsTo(WaterQuality::class, 'pos_id', 'id');
    }

    public function absen()
    {
        return $this->hasMany(Absen::class, 'pos_id', 'id');
    }
}

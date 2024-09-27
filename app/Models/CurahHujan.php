<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurahHujan extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal', 'hujan_otomatis', 'hujan_biasa', 'keterangan', 'pos_id'
    ];

    public function pos() {
        return $this->belongsTo('App\Models\Post','pos_id', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TMA extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal', 'pagi', 'siang','sore', 'keterangan', 'pos_id'
    ];

    public function pos() {
        return $this->belongsTo('App\Models\Post','pos_id', 'id');
    }
}

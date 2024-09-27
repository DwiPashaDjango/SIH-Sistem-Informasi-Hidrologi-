<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaterQuality extends Model
{
    use HasFactory;
    protected $table = 'water_qualities';
    protected $guarded = [];

    public function pos()
    {
        return $this->belongsTo(Post::class, 'pos_id', 'id');
    }

    public function detail()
    {
        return $this->hasMany(WaterQualityDetail::class, 'water_qualitys_id', 'id');
    }
}

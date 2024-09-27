<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaterQualityDetail extends Model
{
    use HasFactory;
    protected $table = 'water_quality_details';
    protected $guarded = [];
}

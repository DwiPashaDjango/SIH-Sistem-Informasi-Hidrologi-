<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jenis extends Model
{
    use HasFactory;

    public function post()
    {
        return $this->hasMany('App\Models\Post','jenis_id', 'id');
    }
}

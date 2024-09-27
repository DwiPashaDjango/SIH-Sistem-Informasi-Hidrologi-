<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubDas extends Model
{
    use HasFactory;
    protected $table = 'sub_das';
    protected $guarded = [];

    public function pos()
    {
        return $this->hasMany(Post::class, 'subdas_id', 'id');
    }
}

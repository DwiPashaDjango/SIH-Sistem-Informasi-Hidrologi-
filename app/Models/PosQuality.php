<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosQuality extends Model
{
    use HasFactory;
    protected $table = 'pos_qualities';
    protected $fillable = ['posts_id'];

    public function pos()
    {
        return $this->belongsTo(Post::class, 'posts_id', 'id');
    }
}

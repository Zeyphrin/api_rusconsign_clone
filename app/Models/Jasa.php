<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jasa extends Model
{
    protected $fillable = [
        'name_jasa', 'desc_jasa', 'price_jasa', 'rating_jasa', 'image_jasa', 'mitra_id',
    ];

    public function mitra()
    {
        return $this->belongsTo(Mitra::class, 'mitra_id'); // 'mitraId' should match the foreign key column name in your database
    }
}

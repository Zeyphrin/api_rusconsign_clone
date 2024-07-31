<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'alamat',
        'mitra_id',
    ];

    public function mitra()
    {
        return $this->belongsTo(Mitra::class);
    }

    public function cods()
    {
        return $this->hasMany(Cod::class);
    }


}

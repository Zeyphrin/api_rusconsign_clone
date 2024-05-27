<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mitra extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id', 'nama_lengkap', 'nis', 'no_dompet_digital', 'image_id_card', 'status', 'pengikut', 'jumlah_product','jumlah_jasa','penilaian'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function Jasas()
    {
        return $this->hasMany(Jasa::class);
    }
    protected $attributes = [
        'jumlah_product' => 0,
        'jumlah_jasa' => 0,
    ];
}

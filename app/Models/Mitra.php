<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mitra extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = ['image_profile','nama', 'nama_toko', 'nis', 'nomor', 'image', 'status', 'pengikut', 'email', 'jumlahproduct', 'jumlahjasa', 'penilaian'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    protected $attributes = [
        'jumlah_product' => 0,
        'jumlah_jasa' => 0,
    ];
    public function profileImage()
    {
        return $this->hasOne(ProfileImage::class, 'mitra_id', 'id');
    }

}

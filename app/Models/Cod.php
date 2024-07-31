<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cod extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'barang_id',
        'lokasi_id',
        'quantity',
        'status_pembayaran',
        'grand_total',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class);
    }


}

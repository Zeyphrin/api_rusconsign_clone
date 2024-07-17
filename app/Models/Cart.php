<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $primaryKey = 'carts_id'; // Specify the primary key
    protected $fillable = ['user_id', 'barang_id', 'quantity', 'total_price'];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }


}

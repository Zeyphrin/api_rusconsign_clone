<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'barang_id',
        'checkout_link',
        'external_id',
        'no_transaction',
        'quantity',
        'status',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

}

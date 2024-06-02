<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name_product', 'desc_product', 'price_product', 'rating_product', 'image', 'mitra_id',
    ];

    /**
     * Get the mitra that owns the product.
     */
    public function mitra()
    {
        return $this->belongsTo(Mitra::class, 'mitra_id','id');
    }


}

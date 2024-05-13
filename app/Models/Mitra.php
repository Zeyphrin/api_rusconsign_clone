<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mitra extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = ['nama_lengkap', 'nis', 'no_dompet_digital', 'image_id_card', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

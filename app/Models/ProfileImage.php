<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileImage extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'bio', 'mitra_id', 'image_profile'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function mitra()
    {
        return $this->belongsTo(Mitra::class, 'mitra_id', 'id');
    }



}

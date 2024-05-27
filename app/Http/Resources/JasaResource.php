<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JasaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "nama_jasa"=>$this->name_jasa,
            "deskripsi" =>$this->desc_jasa,
            "harga"=>$this->price_jasa,
            "rating"=>$this->rating_jasa,
            "image"=>$this->image_jasa,
            "id_mitra"=>$this->mitra_id

        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return
            [
                "nama_product"=>$this->name_product,
                "deskripsi" =>$this->desc_product,
                "harga"=>$this->price_product,
                "rating"=>$this->rating_product,
                "image"=>$this->image,
                "id_mitra"=>$this->mitra_id

            ];
    }
}

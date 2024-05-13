<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            "nama"=>$this->name,
            "email" =>$this->email,
            "pengikut"=>$this->pengikut,
            "jumlah_jasa"=>$this->jumlah_jasa,
            "jumlah_product"=>$this->jumlah_product,
            "penilaian"=>$this->penilaian


        ];
    }
}

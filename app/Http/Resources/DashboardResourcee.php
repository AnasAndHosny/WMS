<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardResourcee extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (int)$this->id,
            'image' => $this->image,
            'name' => (string)$this->name,
            'name_en' => (string)$this->name_en,
            'name_ar' => (string)$this->name_ar,
            'description' => (string)$this->description,
            'description_ar' => (string)$this->description_ar,
            'description_en' => (string)$this->description_en,
            'manufacturer_id' => (int)$this->manufacturer_id,
            'manufacturer' => (string)$this->manufacturer->name,
            'price' => (float)$this->price,
            'subcategory_id' => (int)$this->subcategory_id,
            'subcategory' => (string)$this->subCategory->name,
            'barcode' => (string)$this->barcode,
            'total_quantity' => (int)0,
            'min_quantity' =>  (int)0
        ];
    }
}

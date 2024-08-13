<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $product = [
            'id' => $this->id,
            'image' => $this->image,
            'name' => $this->name,
            'name_en' => $this->name_en,
            'name_ar' => $this->name_ar,
            'description' => $this->description,
            'description_ar' => $this->description_ar,
            'description_en' => $this->description_en,
            'manufacturer_id' => $this->manufacturer_id,
            'manufacturer' => $this->manufacturer->name,
            'price' => $this->price,
            'subcategory_id' => $this->subcategory_id,
            'subcategory' => $this->subCategory->name,
            'barcode' => $this->barcode
        ];
        if (Auth::user()->employee) {
            $product['total_quantity'] = $this->employableProduct ? (int)$this->employableProduct->total_quantity : 0;
            $product['min_quantity'] = $this->employableProduct ? (int)$this->employableProduct->min_quantity : 0;
        }
        return $product;
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderedProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $product = $this->product;
        return [
            'id' => $this->id,
            'product_id' => $product->id,
            'image' => $product->image,
            'name' => $product->name,
            'name_en' => $product->name_en,
            'name_ar' => $product->name_ar,
            'description' => $product->description,
            'description_ar' => $product->description_ar,
            'description_en' => $product->description_en,
            'manufacturer_id' => $product->manufacturer_id,
            'manufacturer' => $product->manufacturer->name,
            'subcategory_id' => $product->subcategory_id,
            'subcategory' => $product->subCategory->name,
            'expiration_date' => $this->expiration_date,
            'quantity' => $this->quantity,
            'cost' => $this->whenHas('cost'),
            'price' => $this->whenHas('price'),
        ];
    }
}

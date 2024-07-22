<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;

class StoredProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $product = $this->product;
        $employable = Auth::user()->employee->employable;
        $employableType = get_class($employable);

        return [
            'id' => $this->id,
            'image' => $product->image,
            'name' => $product->name,
            'name_en' => $product->name_en,
            'name_ar' => $product->name_ar,
            'description' => $product->description,
            'description_ar' => $product->description_ar,
            'description_en' => $product->description_en,
            'manufacturer_id' => $product->manufacturer_id,
            'manufacturer' => $product->manufacturer->name,
            'price' => $product->price,
            'subcategory_id' => $product->subcategory_id,
            'subcategory' => $product->subCategory->name,
            'expiration_date' => $this->expiration_date,
            $this->mergeWhen(
                $this->storable_id == $employable->id && $this->storable_type == $employableType,
                [
                    'valid_quantity' => (int)$this->valid_quantity,
                    'active' => (int)$this->active,
                    'max' => (int)$this->max,
                ],
                [
                    'max' => $this->max < $this->valid_quantity ? (int)$this->max : (int)$this->valid_quantity,
                ]
            ),
        ];
    }
}

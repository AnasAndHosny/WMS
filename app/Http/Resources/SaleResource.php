<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
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
            'sale_num' => '#' . sprintf("%08d", $this->id),
            'buyer_name' => $this->buyer_name,
            'total_price' => (float)$this->total_price,
            'saled_at' => $this->created_at,
            'saled_by_image' => $this->user->image,
            'saled_by_name' => $this->user->name,
            'products' => OrderedProductResource::collection($this->whenLoaded('salesProducts')),
        ];
    }
}

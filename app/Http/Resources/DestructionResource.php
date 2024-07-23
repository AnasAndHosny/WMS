<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DestructionResource extends JsonResource
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
            'destruction_num' => '#' . sprintf("%08d", $this->id),
            'cause' => $this->cause->name,
            'quantity' => (int)$this->quantity,
            'destructioned_at' => $this->created_at,
            'destructioned_by_image' => $this->user->image,
            'destructioned_by_name' => $this->user->name,
            'product' => new ProductResource($this->product)
        ];
    }
}

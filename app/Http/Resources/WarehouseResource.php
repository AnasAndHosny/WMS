<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WarehouseResource extends JsonResource
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
            'name_ar' => (string)$this->name_ar,
            'name_en' => (string)$this->name_en,
            'city' => (string)$this->city->name,
            'state_id' => (int)$this->state_id,
            'state' => (string)$this->state->name,
            'street_address' => $this->street_address,
            'street_address_ar' => $this->street_address_ar,
            'street_address_en' => $this->street_address_en
        ];
    }
}

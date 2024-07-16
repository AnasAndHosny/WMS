<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ManufacturerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'name_ar' => $this->name_ar,
            'name_en' => $this->name_en,
            'city' => $this->city->name,
            'state_id' => $this->state_id,
            'state' => $this->state->name,
            'street_address' => $this->street_address,
            'street_address_ar' => $this->street_address_ar,
            'street_address_en' => $this->street_address_en
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShipmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $shippingCompany = $this->shippingCompany;

        return [
            'id' => $this->id,
            'shipment_num' => '#' . sprintf("%08d", $this->id),
            'shipping_company_id' => (int)$this->shipping_company_id,
            'shipping_company' => $shippingCompany->name,
            'shipping_company_city' => $shippingCompany->city->name,
            'shipping_company_state' => $shippingCompany->state->name,
            'shipping_company_address' => $shippingCompany->street_address,
            'driver_name' => $this->driver_name,
            'cost' => (float)$this->cost,
            'shipped_at' => $this->created_at,
            'order' => new OrderResource($this->whenLoaded('order')),
        ];
    }
}

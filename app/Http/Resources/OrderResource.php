<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $orderedFrom = $this->orderableFrom;
        $orderedBy = $this->orderableBy;
        return [
            'id' => $this->id,
            'order_num' => '#' . sprintf("%08d", $this->id),
            'ordered_from_type' => $this->orderable_from_type,
            'ordered_from_id' => (int)$this->orderable_from_id,
            'ordered_from' => $orderedFrom->name,
            'ordered_from_image' => $orderedFrom->image,
            'ordered_from_city' => $orderedFrom->city->name,
            'ordered_from_state' => $orderedFrom->state->name,
            'ordered_from_address' => $orderedFrom->street_address,
            'ordered_by_type' => $this->orderable_by_type,
            'ordered_by_id' => (int)$this->orderable_by_id,
            'ordered_by' => $orderedBy->name,
            'ordered_by_image' => $orderedBy->image,
            'ordered_by_city' => $orderedBy->city->name,
            'ordered_by_state' => $orderedBy->state->name,
            'ordered_by_address' => $orderedBy->street_address,
            'status_id' => (int)$this->status_id,
            'status' => $this->status->name,
            'order_cost' => (float)$this->order_cost,
            'shipping_cost' => $this->shipment ? (float)$this->shipment->cost : (float)($this->total_cost - $this->order_cost),
            'total_cost' => (float)$this->total_cost,
            'ordered_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'products' => OrderedProductResource::collection($this->whenLoaded('orderedProducts')),
        ];
    }
}

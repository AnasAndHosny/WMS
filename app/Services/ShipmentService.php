<?php

namespace App\Services;

use App\Models\Shipment;
use App\Models\OrderStatus;
use App\Models\StoredProduct;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ShipmentResource;
use App\Http\Resources\ShipmentCollection;
use App\Models\EmployableProduct;

class ShipmentService
{
    public function index(): array
    {
        $shipments = Shipment::myShipment()
            ->with('order')
            ->paginate();

        $shipments = new ShipmentCollection($shipments);
        $message = __('messages.index_success', ['class' => __('shipments')]);
        $code = 200;
        return ['data' => $shipments, 'message' => $message, 'code' => $code];
    }

    public function store($request): array
    {
        $shipment = DB::transaction(function () use ($request): Shipment {
            $shipment = Shipment::query()->create([
                'order_id' => $request['order_id'],
                'shipping_company_id' => $request['shipping_company_id'],
                'driver_name' => $request['driver_name'],
                'cost' => $request['cost'],
            ]);

            $order = $shipment->order;
            $order->update([
                'status_id' => OrderStatus::findByName('Under Shipping')->id
            ]);
            $order->increment('total_cost', $request['cost'] * 100);

            $orderedProducts = $order->orderedProducts;
            foreach ($orderedProducts as $orderedProduct) {
                StoredProduct::query()
                    ->where('storable_type', $order->orderable_from_type)
                    ->where('storable_id', $order->orderable_from_id)
                    ->where('product_id', $orderedProduct['product_id'])
                    ->where('expiration_date', $orderedProduct['expiration_date'])
                    ->first()
                    ->decrement('valid_quantity', $orderedProduct['quantity']);

                EmployableProduct::query()
                    ->where('employable_type', $order->orderable_from_type)
                    ->where('employable_id', $order->orderable_from_id)
                    ->where('product_id', $orderedProduct['product_id'])
                    ->first()
                    ->decrement('total_quantity', $orderedProduct['quantity']);
            }

            return $shipment;
        });

        $shipment = new ShipmentResource($shipment->load('order'));
        $message = __('messages.store_success', ['class' => __('shipment')]);
        $code = 201;
        return ['data' => $shipment, 'message' => $message, 'code' => $code];
    }

    public function show(Shipment $shipment): array
    {
        $shipment = new ShipmentResource($shipment->load('order'));
        $message = __('messages.show_success', ['class' => __('shipment')]);
        $code = 200;
        return ['data' => $shipment, 'message' => $message, 'code' => $code];
    }
}

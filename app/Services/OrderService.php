<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Warehouse;
use App\Models\OrderStatus;
use App\Models\Manufacturer;
use App\Models\StoredProduct;
use App\Models\OrderedProduct;
use App\Models\EmployableProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderCollection;

class OrderService
{
    public function buyOrdersList(): array
    {
        $buyOrders = Auth::user()
            ->buyOrders()
            ->orderBy('id', 'DESC')->paginate();

        $buyOrders = new OrderCollection($buyOrders);
        $message = __('messages.index_success', ['class' => __('buy orders')]);
        $code = 200;
        return ['data' => $buyOrders, 'message' => $message, 'code' => $code];
    }

    public function sellOrdersList(): array
    {
        $sellOrders = Auth::user()
            ->sellOrders()
            ->orderBy('id', 'DESC')->paginate();

        $sellOrders = new OrderCollection($sellOrders);
        $message = __('messages.index_success', ['class' => __('sell orders')]);
        $code = 200;
        return ['data' => $sellOrders, 'message' => $message, 'code' => $code];
    }

    public function manufacturerOrdersList(): array
    {
        $manufacturerOrders = Auth::user()
            ->manufacturerOrders()
            ->orderBy('id', 'DESC')->paginate();

        $manufacturerOrders = new OrderCollection($manufacturerOrders);
        $message = __('messages.index_success', ['class' => __('manufacturer orders')]);
        $code = 200;
        return ['data' => $manufacturerOrders, 'message' => $message, 'code' => $code];
    }

    public function storeWarehouseOrder($request): array
    {
        $order = DB::transaction(function () use ($request): Order {
            $employable = Auth::user()->employee->employable;
            $orderedByModel = get_class($employable);
            $orderedById = $employable->id;

            // Calculate the total cost and prepare ordered products
            $totalCost = 0;
            $orderedProducts = [];
            foreach ($request['products'] as $product) {
                $storedProduct = StoredProduct::find($product['id']);
                $productDetail = $storedProduct->product;
                $orderedProducts[] = new OrderedProduct([
                    'product_id' => $productDetail->id,
                    'expiration_date' => $storedProduct->expiration_date,
                    'quantity' => $product['quantity'],
                    'cost' => $productDetail->price,
                ]);
                $totalCost += $productDetail->price * $product['quantity'];
            }

            $totalCost = round($totalCost, 2);

            // Create and save the order
            $order = Order::query()->create([
                'orderable_from_type' => Warehouse::class,
                'orderable_from_id' => $request['warehouse_id'],
                'orderable_by_type' => $orderedByModel,
                'orderable_by_id' => $orderedById,
                'status_id' => OrderStatus::findByName('Pending')->id,
                'order_cost' => $totalCost,
                'total_cost' => $totalCost,
                'user_id' => Auth::user()->getAuthIdentifier()
            ]);

            // Attach ordered products to the order
            $order->orderedProducts()->saveMany($orderedProducts);

            return $order;
        });

        $order = new OrderResource($order->load('orderedProducts'));
        $message = __('messages.store_success', ['class' => __('order')]);
        $code = 201;
        return ['data' => $order, 'message' => $message, 'code' => $code];
    }

    public function storeManufacturerOrder($request): array
    {
        $order = DB::transaction(function () use ($request): Order {
            $employable = Auth::user()->employee->employable;
            $orderedByModel = get_class($employable);
            $orderedById = $employable->id;

            // Calculate the total cost and prepare ordered products
            $orderCost = 0;
            $orderedProducts = [];
            foreach ($request['products'] as $product) {
                $orderedProducts[] = new OrderedProduct([
                    'product_id' => $product['id'],
                    'expiration_date' => $product['exp'],
                    'quantity' => $product['quantity'],
                    'cost' => $product['cost'],
                ]);
                $orderCost += $product['cost'] * $product['quantity'];
            }

            $totalCost = round($orderCost + $request['shipping_cost'], 2);
            $orderCost = round($orderCost, 2);

            // Create and save the order
            $order = Order::query()->create([
                'orderable_from_type' => Manufacturer::class,
                'orderable_from_id' => $request['manufacturer_id'],
                'orderable_by_type' => $orderedByModel,
                'orderable_by_id' => $orderedById,
                'status_id' => OrderStatus::findByName('Pending')->id,
                'order_cost' => $orderCost,
                'total_cost' => $totalCost,
                'user_id' => Auth::user()->getAuthIdentifier()
            ]);

            // Attach ordered products to the order
            $order->orderedProducts()->saveMany($orderedProducts);

            return $order;
        });

        $order = new OrderResource($order->load('orderedProducts'));
        $message = __('messages.store_success', ['class' => __('order')]);
        $code = 201;
        return ['data' => $order, 'message' => $message, 'code' => $code];
    }

    public function show(Order $order): array
    {
        $order = new OrderResource($order->load('orderedProducts'));
        $message = __('messages.show_success', ['class' => __('order')]);
        $code = 200;
        return ['data' => $order, 'message' => $message, 'code' => $code];
    }

    public function updateManufacturerOrder($request, Order $order): array
    {
        $orderStatus = $order->status;

        if ($orderStatus->name_en != 'Delivered') {
            $order = DB::transaction(function () use ($request, $order): Order {
                // Calculate the total cost and prepare ordered products
                $orderCost = 0;
                $orderedProducts = [];
                foreach ($request['products'] as $product) {
                    $orderedProducts[] = new OrderedProduct([
                        'product_id' => $product['id'],
                        'expiration_date' => $product['exp'],
                        'quantity' => $product['quantity'],
                        'cost' => $product['cost'],
                    ]);
                    $orderCost += $product['cost'] * $product['quantity'];
                }

                $totalCost = round($orderCost + $request['shipping_cost'], 2);
                $orderCost = round($orderCost, 2);

                // Create and save the order
                $order->update([
                    'orderable_from_type' => Manufacturer::class,
                    'orderable_from_id' => $request['manufacturer_id'],
                    'status_id' => $request['status_id'],
                    'order_cost' => $orderCost,
                    'total_cost' => $totalCost,
                ]);

                $order->orderedProducts()->delete();

                // Attach ordered products to the order
                $order->orderedProducts()->saveMany($orderedProducts);

                return Order::find($order->id);
            });

            $message = __('messages.update_success', ['class' => __('order')]);
            $code = 200;
        } else {
            $message = __('messages.update_order_failed', ['status' => $order->status->name]);
            $code = 400;
        }

        $order = new OrderResource($order->load('orderedProducts'));
        return ['data' => $order, 'message' => $message, 'code' => $code];
    }

    public function accept(Order $order): array
    {
        $orderStatus = $order->status;
        if ($orderStatus->name_en == 'Pending') {
            $order->update([
                'status_id' => OrderStatus::findByName('Under Preparing')->id
            ]);

            $order = Order::find($order->id);
            $message = __('messages.update_success', ['class' => __('order status')]);
            $code = 200;
        } else {
            $message = __('messages.update_order_status_failed', ['status' => $order->status->name]);
            $code = 400;
        }

        $order = new OrderResource($order->load('orderedProducts'));
        return ['data' => $order, 'message' => $message, 'code' => $code];
    }

    public function reject(Order $order): array
    {
        $orderStatus = $order->status;
        if ($orderStatus->name_en == 'Pending') {
            $orderStatusId = OrderStatus::findByName('Rejected')->id;
        } elseif ($orderStatus->name_en == 'Under Preparing') {
            $orderStatusId = OrderStatus::findByName('Cancelled')->id;
        }
        if (isset($orderStatusId)) {
            $order->update([
                'status_id' => $orderStatusId
            ]);

            $order = Order::find($order->id);
            $message = __('messages.update_success', ['class' => __('order status')]);
            $code = 200;
        } else {
            $message = __('messages.update_order_status_failed', ['status' => $order->status->name]);
            $code = 400;
        }

        $order = new OrderResource($order->load('orderedProducts'));
        return ['data' => $order, 'message' => $message, 'code' => $code];
    }

    public function receive(Order $order): array
    {
        $orderStatus = $order->status;
        if ($orderStatus->name_en == 'Under Shipping' || ($order->orderable_from_type == 'Manufacturer' && $orderStatus->name_en != 'Delivered')) {
            $order = DB::transaction(function () use ($order): Order {
                $order->update([
                    'status_id' => OrderStatus::findByName('Delivered')->id
                ]);

                $orderedProducts = $order->orderedProducts;
                foreach ($orderedProducts as $orderedProduct) {
                    StoredProduct::query()
                        ->where('storable_type', $order->getRawOriginal('orderable_by_type'))
                        ->where('storable_id', $order->orderable_by_id)
                        ->where('product_id', $orderedProduct['product_id'])
                        ->where('expiration_date', $orderedProduct['expiration_date'])
                        ->firstOrCreate([
                            'storable_type' => $order->getRawOriginal('orderable_by_type'),
                            'storable_id' => $order->orderable_by_id,
                            'product_id' => $orderedProduct['product_id'],
                            'expiration_date' => $orderedProduct['expiration_date'],
                        ])
                        ->increment('valid_quantity', $orderedProduct['quantity']);

                    EmployableProduct::query()
                        ->where('employable_type', $order->getRawOriginal('orderable_by_type'))
                        ->where('employable_id', $order->orderable_by_id)
                        ->where('product_id', $orderedProduct['product_id'])
                        ->firstOrCreate([
                            'employable_type' => $order->getRawOriginal('orderable_by_type'),
                            'employable_id' => $order->orderable_by_id,
                            'product_id' => $orderedProduct['product_id'],
                        ])
                        ->increment('total_quantity', $orderedProduct['quantity']);
                }

                return $order;
            });

            $order = Order::find($order->id);
            $message = __('messages.update_success', ['class' => __('order status')]);
            $code = 200;
        } else {
            $message = __('messages.update_order_status_failed', ['status' => $order->status->name]);
            $code = 400;
        }

        $order = new OrderResource($order->load('orderedProducts'));
        return ['data' => $order, 'message' => $message, 'code' => $code];
    }

    public function delete(Order $order): array
    {
        $orderStatus = $order->status;
        if ($orderStatus->name_en == 'Pending') {
            $orderStatusId = OrderStatus::findByName('Deleted')->id;
            $order->update([
                'status_id' => $orderStatusId
            ]);

            $order = Order::find($order->id);
            $message = __('messages.update_success', ['class' => __('order status')]);
            $code = 200;
        } else {
            $message = __('messages.update_order_status_failed', ['status' => $order->status->name]);
            $code = 400;
        }

        $order = new OrderResource($order->load('orderedProducts'));
        return ['data' => $order, 'message' => $message, 'code' => $code];
    }
}

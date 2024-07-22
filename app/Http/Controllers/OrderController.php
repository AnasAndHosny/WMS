<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Order;
use App\Services\OrderService;
use App\Http\Responses\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Order\StoreWarehouseOrderRequest;
use App\Http\Requests\Order\StoreManufacturerOrderRequest;
use App\Http\Requests\Order\UpdateManufacturerOrderRequest;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Display a listing of the resource.
     */
    public function buyOrdersList(Request $request): JsonResponse
    {
        $data = [];
        try {
            $data = $this->orderService->buyOrdersList($request);
            return Response::Success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function sellOrdersList(Request $request): JsonResponse
    {
        $data = [];
        try {
            $data = $this->orderService->sellOrdersList($request);
            return Response::Success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function manufacturerOrdersList(Request $request): JsonResponse
    {
        $data = [];
        try {
            $data = $this->orderService->manufacturerOrdersList($request);
            return Response::Success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeWarehouseOrder(StoreWarehouseOrderRequest $request): JsonResponse
    {
        $data = [];
        try {
            $data = $this->orderService->storeWarehouseOrder($request->validated());
            return Response::Success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    public function storeManufacturerOrder(StoreManufacturerOrderRequest $request): JsonResponse
    {
        $data = [];
        try {
            $data = $this->orderService->storeManufacturerOrder($request->validated());
            return Response::Success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order): JsonResponse
    {
        $data = [];
        try {
            $data = $this->orderService->show($order);
            return Response::Success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateManufacturerOrder(UpdateManufacturerOrderRequest $request, Order $order)
    {
        $data = [];
        try {
            $data = $this->orderService->updateManufacturerOrder($request, $order);
            return Response::Success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    /**
     * accept the specified resource.
     */
    public function accept(Order $order): JsonResponse
    {
        $data = [];
        try {
            $data = $this->orderService->accept($order);
            return Response::Success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    /**
     * delete the specified resource.
     */
    public function reject(Order $order): JsonResponse
    {
        $data = [];
        try {
            $data = $this->orderService->reject($order);
            return Response::Success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    /**
     * accept the specified resource.
     */
    public function receive(Order $order): JsonResponse
    {
        $data = [];
        try {
            $data = $this->orderService->receive($order);
            return Response::Success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    /**
     * delete the specified resource.
     */
    public function delete(Order $order): JsonResponse
    {
        $data = [];
        try {
            $data = $this->orderService->delete($order);
            return Response::Success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }
}

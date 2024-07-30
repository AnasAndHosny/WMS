<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Http\Responses\Response;
use Illuminate\Http\JsonResponse;
use App\Services\WarehouseService;
use App\Http\Requests\Warehouse\StoreWarehouseRequest;
use App\Http\Requests\Warehouse\UpdateWarehouseRequest;

class WarehouseController extends Controller
{
    private WarehouseService $warehouseService;

    public function __construct(WarehouseService $warehouseService)
    {
        $this->warehouseService = $warehouseService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $data = [];
        try {
            $data = $this->warehouseService->index($request);
            return Response::Success($data['warehouse'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWarehouseRequest $request): JsonResponse
    {
        $data = [];
        try {
            $data = $this->warehouseService->store($request);
            return Response::Success($data['warehouse'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Warehouse $warehouse): JsonResponse
    {
        $data = [];
        try {
            $data = $this->warehouseService->show($warehouse);
            return Response::Success($data['warehouse'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWarehouseRequest $request, Warehouse $warehouse): JsonResponse
    {
        $data = [];
        try {
            $data = $this->warehouseService->update($request, $warehouse);
            return Response::Success($data['warehouse'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    public function showDistributionCenters(Request $request): JsonResponse
    {
        $data = [];
        try {
            $data = $this->warehouseService->showDistributionCenters($request);
            return Response::Success($data['distributionCenter'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    public function continueManager(Warehouse $warehouse): JsonResponse
    {
        $data = [];
        try {
            $data = $this->warehouseService->continueManager($warehouse);
            return Response::Success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }
}

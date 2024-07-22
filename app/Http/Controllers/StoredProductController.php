<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoredProduct\StoreStoredProductRequest;
use App\Http\Requests\StoredProduct\UpdateStoredProductRequest;
use App\Http\Responses\Response;
use App\Models\StoredProduct;
use App\Models\Warehouse;
use App\Services\StoredProductService;
use Illuminate\Http\JsonResponse;
use Throwable;

class StoredProductController extends Controller
{
    private StoredProductService $storedProductService;

    public function __construct(StoredProductService $storedProductService)
    {
        $this->storedProductService = $storedProductService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $data = [];
        try {
            $data = $this->storedProductService->index();
            return Response::Success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    public function warehousesProductList(Warehouse $warehouse): JsonResponse
    {
        $data = [];
        try {
            $data = $this->storedProductService->warehousesProductList($warehouse);
            return Response::Success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    public function warehouseProductList(): JsonResponse
    {
        $data = [];
        try {
            $data = $this->storedProductService->warehouseProductList();
            return Response::Success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStoredProductRequest $request): JsonResponse
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(StoredProduct $storedProduct): JsonResponse
    {
        $data = [];
        try {
            $data = $this->storedProductService->show($storedProduct);
            return Response::Success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    public function update(UpdateStoredProductRequest $request, StoredProduct $storedProduct): JsonResponse
    {
        $data = [];
        try {
            $data = $this->storedProductService->update($request, $storedProduct);
            return Response::Success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StoredProduct $storedProduct)
    {
        //
    }
}
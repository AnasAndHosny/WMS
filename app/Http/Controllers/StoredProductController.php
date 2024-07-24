<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoredProduct\StoreStoredProductRequest;
use App\Http\Requests\StoredProduct\UpdateStoredProductRequest;
use App\Http\Responses\Response;
use App\Models\StoredProduct;
use App\Models\Warehouse;
use App\Services\StoredProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
    public function index(Request $request): JsonResponse
    {
        $data = [];
        try {
            $data = $this->storedProductService->index($request);
            return Response::Success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    public function warehousesProductList(Request $request, Warehouse $warehouse): JsonResponse
    {
        $data = [];
        try {
            $data = $this->storedProductService->warehousesProductList($request, $warehouse);
            return Response::Success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    public function warehouseProductList(Request $request): JsonResponse
    {
        $data = [];
        try {
            $data = $this->storedProductService->warehouseProductList($request);
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

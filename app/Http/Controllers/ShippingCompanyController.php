<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShippingCompany\StoreShippingCompanyRequest;
use App\Http\Requests\ShippingCompany\UpdateShippingCompanyRequest;
use App\Http\Responses\Response;
use App\Models\ShippingCompany;
use App\Services\ShippingCompanyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class ShippingCompanyController extends Controller
{
    private ShippingCompanyService $shippingCompanyService;

    public function __construct(ShippingCompanyService $shippingCompanyService)
    {
        $this->shippingCompanyService = $shippingCompanyService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $data = [];
        try {
            $data = $this->shippingCompanyService->index($request);
            return Response::Success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreShippingCompanyRequest $request)
    {
        $data = [];
        try {
            $data = $this->shippingCompanyService->store($request);
            return Response::Success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ShippingCompany $shippingCompany)
    {
        $data = [];
        try {
            $data = $this->shippingCompanyService->show($shippingCompany);
            return Response::Success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateShippingCompanyRequest $request, ShippingCompany $shippingCompany)
    {
        $data = [];
        try {
            $data = $this->shippingCompanyService->update($request, $shippingCompany);
            return Response::Success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }
}

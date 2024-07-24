<?php

namespace App\Http\Controllers;

use App\Http\Requests\DistributionCenter\StoreDistributionCenterRequest;
use App\Http\Requests\DistributionCenter\UpdateDistributionCenterRequest;
use App\Http\Responses\Response;
use App\Models\DistributionCenter;
use App\Services\DistributionCenterService;
use Illuminate\Http\JsonResponse;
use Throwable;

class DistributionCenterController extends Controller
{
    private DistributionCenterService $distributionCenterService;

    public function __construct(DistributionCenterService $distributionCenterService)
    {
        $this->distributionCenterService = $distributionCenterService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $data = [];
        try {
            $data = $this->distributionCenterService->index();
            return Response::Success($data['distributionCenter'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDistributionCenterRequest $request): JsonResponse
    {
        $data = [];
        try {
            $data = $this->distributionCenterService->store($request);
            return Response::Success($data['distributionCenter'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(DistributionCenter $distributionCenter): JsonResponse
    {
        $data = [];
        try {
            $data = $this->distributionCenterService->show($distributionCenter);
            return Response::Success($data['distributionCenter'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDistributionCenterRequest $request, DistributionCenter $distributionCenter): JsonResponse
    {
        $data = [];
        try {
            $data = $this->distributionCenterService->update($request, $distributionCenter);
            return Response::Success($data['distributionCenter'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    public function continueManager(DistributionCenter $distributionCenter): JsonResponse
    {
        $data = [];
        try {
            $data = $this->distributionCenterService->continueManager($distributionCenter);
            return Response::Success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }
}

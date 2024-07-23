<?php

namespace App\Http\Controllers;

use App\Http\Requests\Destruction\StoreDestructionRequest;
use App\Http\Requests\Destruction\UpdateDestructionRequest;
use App\Http\Responses\Response;
use App\Models\Destruction;
use App\Models\StoredProduct;
use App\Services\DestructionService;
use Illuminate\Http\JsonResponse;
use Throwable;

class DestructionController extends Controller
{
    private DestructionService $destructionService;

    public function __construct(DestructionService $destructionService)
    {
        $this->destructionService = $destructionService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [];
        try {
            $data = $this->destructionService->index();
            return Response::Success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDestructionRequest $request, StoredProduct $storedProduct): JsonResponse
    {
        $data = [];
        try {
            $data = $this->destructionService->store($request, $storedProduct);
            return Response::Success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Destruction $destruction)
    {
        $data = [];
        try {
            $data = $this->destructionService->show($destruction);
            return Response::Success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDestructionRequest $request, Destruction $destruction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Destruction $destruction)
    {
        //
    }
}

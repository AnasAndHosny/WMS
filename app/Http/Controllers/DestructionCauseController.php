<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestructionCause\StoreDestructionCauseRequest;
use App\Http\Requests\DestructionCause\UpdateDestructionCauseRequest;
use App\Http\Responses\Response;
use App\Models\DestructionCause;
use App\Services\DestructionCauseService;
use Illuminate\Http\JsonResponse;
use Throwable;

class DestructionCauseController extends Controller
{
    private DestructionCauseService $destructionCauseService;

    public function __construct(DestructionCauseService $destructionCauseService)
    {
        $this->destructionCauseService = $destructionCauseService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $data = [];
        try {
            $data = $this->destructionCauseService->index();
            return Response::Success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDestructionCauseRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(DestructionCause $destructionCause)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DestructionCause $destructionCause)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDestructionCauseRequest $request, DestructionCause $destructionCause)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestructionCause $destructionCause)
    {
        //
    }
}

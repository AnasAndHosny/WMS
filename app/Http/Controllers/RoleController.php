<?php

namespace App\Http\Controllers;

use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Responses\Response;
use App\Services\RoleService;
use Illuminate\Http\JsonResponse;
use Throwable;


class RoleController extends Controller
{
    private RoleService $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * Display a listing of the warehouse roles.
     */
    public function warehouseRolesList(): JsonResponse
    {
        $data = [];
        try {
            $data = $this->roleService->warehouseRolesList();
            return Response::Success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    /**
     * Display a listing of the distribution center roles.
     */
    public function distributionCenterRolesList(): JsonResponse
    {
        $data = [];
        try {
            $data = $this->roleService->distributionCenterRolesList();
            return Response::Success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    public function store(StoreRoleRequest $request): JsonResponse
    {
        $data = [];
        try {
            $data = $this->roleService->store($request);
            return Response::Success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }
}

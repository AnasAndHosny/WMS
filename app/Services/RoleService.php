<?php

namespace App\Services;

use App\Models\Role;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\RoleResource;

class RoleService
{

    public function warehouseRolesList(): array
    {
        $role = RoleResource::collection(Role::ofTypeName('Warehouse')->get());
        $message = __('messages.index_success', ['class' => __('roles')]);
        $code = 200;
        return ['data' => $role, 'message' => $message, 'code' => $code];
    }

    public function distributionCenterRolesList(): array
    {
        $role = RoleResource::collection(Role::ofTypeName('DistributionCenter')->get());
        $message = __('messages.index_success', ['class' => __('roles')]);
        $code = 200;
        return ['data' => $role, 'message' => $message, 'code' => $code];
    }

    public function store($request): array
    {
        $role = DB::transaction(function () use ($request): Role {
            $role = Role::create([
                'name' => $request['name'],
                'guard_name' => 'web',
                'type' => $request['type']
            ]);

            $role->syncPermissions($request['permissions']);

            return $role;
        });

        $role = new RoleResource($role->load('permissions'));
        $message = __('messages.store_success', ['class' => __('role')]);
        $code = 201;
        return ['data' => $role, 'message' => $message, 'code' => $code];
    }
}

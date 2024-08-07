<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\State;
use App\Models\Warehouse;
use App\Services\ImageService;
use App\Models\DistributionCenter;
use App\Queries\WarehousesListQuery;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\WarehouseResource;
use App\Queries\DistributionCentersListQuery;
use App\Http\Resources\DistributionCenterResource;

class WarehouseService
{
    public function index($request): array
    {
        $employee = Auth::user()->employee;

        $query = Warehouse::query();

        if ($employee) {
            $userWarehouse = Auth::user()->employee->employable;
            $query->when(get_class($userWarehouse) == Warehouse::class)
                ->where('id', '!=', $userWarehouse->id);
        }

        $warehouse = new WarehousesListQuery($query, $request);
        $warehouse = WarehouseResource::collection($query->get());
        $message = __('messages.index_success', ['class' => __('warehouses')]);
        $code = 200;
        return ['warehouse' => $warehouse, 'message' => $message, 'code' => $code];
    }

    public function store($request): array
    {
        $image = ImageService::store($request);
        $warehouse = Warehouse::query()->create([
            'image' => $image,
            'name_ar' => $request['name_ar'],
            'name_en' => $request['name_en'],
            'state_id' => $request['state_id'],
            'street_address_ar' => $request['street_address_ar'],
            'street_address_en' => $request['street_address_en']
        ]);

        $warehouse = new WarehouseResource($warehouse);
        $message = __('messages.store_success', ['class' => __('warehouse')]);
        $code = 201;
        return ['warehouse' => $warehouse, 'message' => $message, 'code' => $code];
    }

    public function show(Warehouse $warehouse): array
    {
        $warehouse = new WarehouseResource($warehouse);
        $message = __('messages.show_success', ['class' => __('warehouse')]);
        $code = 200;
        return ['warehouse' => $warehouse, 'message' => $message, 'code' => $code];
    }

    public function update($request, Warehouse $warehouse): array
    {
        $image = ImageService::update($request, $warehouse);
        $warehouse->update([
            'image' => $image,
            'name_ar' => $request['name_ar'] ?? $warehouse['name_ar'],
            'name_en' => $request['name_en'] ?? $warehouse['name_en'],
            'state_id' => $request['state_id'] ?? $warehouse['state_id'],
            'street_address_ar' => $request['street_address_ar'] ?? $warehouse['street_address_ar'],
            'street_address_en' => $request['street_address_en'] ?? $warehouse['street_address_en']
        ]);

        $warehouse = new WarehouseResource($warehouse);
        $message = __('messages.update_success', ['class' => __('warehouse')]);
        $code = 200;
        return ['warehouse' => $warehouse, 'message' => $message, 'code' => $code];
    }

    public function showDistributionCenters($request): array
    {
        $warehouse = Auth::user()->employee->employable_id;

        $distributionCenter = new DistributionCentersListQuery(DistributionCenter::where('warehouse_id', $warehouse), $request);
        $distributionCenter = DistributionCenterResource::collection($distributionCenter->get());
        $message = __('messages.index_success', ['class' => __('distribution centers')]);
        $code = 200;
        return ['distributionCenter' => $distributionCenter, 'message' => $message, 'code' => $code];
    }

    public function continueManager(Warehouse $warehouse): array
    {
        $user = Auth::user();
        $userId = $user->getAuthIdentifier();

        $user->employee()->delete();

        $employee = $warehouse->employees()->create([
            'full_name' => 'admin',
            'gender' => 'male',
            'birthday' => Carbon::now()->format('Y-m-d'),
            'phone_number' => null,
            'address' => null,
            'ssn' => '11111111111',
            'user_id' => $userId,
            'state_id' => State::first()->id,
        ]);

        $employee->update([
            'full_name' => 'admin ' . $employee->id,
        ]);

        $warehouse = new WarehouseResource($warehouse);
        $message = __('You are a warehouse manager now.');
        $code = 200;
        return ['data' => $warehouse, 'message' => $message, 'code' => $code];
    }
}

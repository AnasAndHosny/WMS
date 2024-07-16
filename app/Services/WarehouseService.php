<?php

namespace App\Services;

use App\Models\Warehouse;
use App\Services\ImageService;
use App\Models\DistributionCenter;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\WarehouseResource;
use App\Http\Resources\DistributionCenterResource;

class WarehouseService
{
    public function index(): array
    {
        $query = Warehouse::all();
        if (Auth::user()->roles->first()->type == 1) {
        $query->where('id', '!=', Auth::user()->employee->employable_id);
        }
        $warehouse = WarehouseResource::collection($query);
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

    public function showDistributionCenters(): array
    {
        $warehouse = Auth::user()->employee->employable_id;

        $distributionCenter = DistributionCenterResource::collection(DistributionCenter::where('warehouse_id', $warehouse)->get());
        $message = __('messages.index_success', ['class' => __('distribution centers')]);
        $code = 200;
        return ['distributionCenter' => $distributionCenter, 'message' => $message, 'code' => $code];
    }
}

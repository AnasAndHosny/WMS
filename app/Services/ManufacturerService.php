<?php

namespace App\Services;

use App\Models\Manufacturer;
use App\Http\Resources\ManufacturerResource;

class ManufacturerService
{
    public function index(): array
    {
        $manufacturer = ManufacturerResource::collection(manufacturer::all());
        $message = __('messages.index_success', ['class' => __('manufacturers')]);
        $code = 200;
        return ['data' => $manufacturer, 'message' => $message, 'code' => $code];
    }

    public function store($request): array
    {
        $manufacturer = manufacturer::query()->create([
            'name_ar' => $request['name_ar'],
            'name_en' => $request['name_en'],
            'state_id' => $request['state_id'],
            'street_address_ar' => $request['street_address_ar'],
            'street_address_en' => $request['street_address_en']
        ]);

        $manufacturer = new ManufacturerResource($manufacturer);
        $message = __('messages.store_success', ['class' => __('manufacturer')]);
        $code = 201;
        return ['data' => $manufacturer, 'message' => $message, 'code' => $code];
    }

    public function show(Manufacturer $manufacturer): array
    {
        $manufacturer = new ManufacturerResource($manufacturer);
        $message = __('messages.show_success', ['class' => __('manufacturer')]);
        $code = 200;
        return ['data' => $manufacturer, 'message' => $message, 'code' => $code];
    }

    public function update($request, manufacturer $manufacturer): array
    {
        $manufacturer->update([
            'name_ar' => $request['name_ar'] ?? $manufacturer['name_ar'],
            'name_en' => $request['name_en'] ?? $manufacturer['name_en'],
            'state_id' => $request['state_id'] ?? $manufacturer['state_id'],
            'street_address_ar' => $request['street_address_ar'] ?? $manufacturer['street_address_ar'],
            'street_address_en' => $request['street_address_en'] ?? $manufacturer['street_address_en']
        ]);

        $manufacturer = new ManufacturerResource($manufacturer);
        $message = __('messages.update_success', ['class' => __('manufacturer')]);
        $code = 200;
        return ['data' => $manufacturer, 'message' => $message, 'code' => $code];
    }
}

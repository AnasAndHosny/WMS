<?php

namespace App\Services;

use App\Http\Resources\ShippingCompanyResource;
use App\Models\ShippingCompany;
use App\Queries\ShippingCompaniesListQuery;

class ShippingCompanyService
{
    public function index($request): array
    {
        $shippingCompany = new ShippingCompaniesListQuery(ShippingCompany::query(), $request);
        
        $shippingCompany = ShippingCompanyResource::collection($shippingCompany->get());
        $message = __('messages.index_success', ['class' => __('shipping companies')]);
        $code = 200;
        return ['data' => $shippingCompany, 'message' => $message, 'code' => $code];
    }

    public function store($request): array
    {
        $shippingCompany = ShippingCompany::query()->create([
            'name_ar' => $request['name_ar'],
            'name_en' => $request['name_en'],
            'state_id' => $request['state_id'],
            'street_address_ar' => $request['street_address_ar'],
            'street_address_en' => $request['street_address_en']
        ]);

        $shippingCompany = new ShippingCompanyResource($shippingCompany);
        $message = __('messages.store_success', ['class' => __('shipping company')]);
        $code = 201;
        return ['data' => $shippingCompany, 'message' => $message, 'code' => $code];
    }

    public function show(ShippingCompany $shippingCompany): array
    {
        $shippingCompany = new ShippingCompanyResource($shippingCompany);
        $message = __('messages.show_success', ['class' => __('shipping company')]);
        $code = 200;
        return ['data' => $shippingCompany, 'message' => $message, 'code' => $code];
    }

    public function update($request, ShippingCompany $shippingCompany): array
    {
        $shippingCompany->update([
            'name_ar' => $request['name_ar'] ?? $shippingCompany['name_ar'],
            'name_en' => $request['name_en'] ?? $shippingCompany['name_en'],
            'state_id' => $request['state_id'] ?? $shippingCompany['state_id'],
            'street_address_ar' => $request['street_address_ar'] ?? $shippingCompany['street_address_ar'],
            'street_address_en' => $request['street_address_en'] ?? $shippingCompany['street_address_en']
        ]);

        $shippingCompany = new ShippingCompanyResource($shippingCompany);
        $message = __('messages.update_success', ['class' => __('shipping company')]);
        $code = 200;
        return ['data' => $shippingCompany, 'message' => $message, 'code' => $code];
    }
}

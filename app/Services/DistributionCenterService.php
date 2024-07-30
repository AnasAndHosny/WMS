<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\State;
use Illuminate\Http\Request;
use App\Models\DistributionCenter;
use Illuminate\Support\Facades\Auth;
use App\Queries\DistributionCentersListQuery;
use App\Http\Resources\DistributionCenterResource;

class DistributionCenterService
{
    public function index(Request $request): array
    {
        $distributionCenter = new DistributionCentersListQuery(DistributionCenter::query(), $request);
        $distributionCenter = DistributionCenterResource::collection($distributionCenter->get());
        $message = __('messages.index_success', ['class' => __('distribution centers')]);
        $code = 200;
        return ['distributionCenter' => $distributionCenter, 'message' => $message, 'code' => $code];
    }

    public function store($request): array
    {
        $image = ImageService::store($request);
        $distributionCenter = DistributionCenter::query()->create([
            'image' => $image,
            'name_ar' => $request['name_ar'],
            'name_en' => $request['name_en'],
            'state_id' => $request['state_id'],
            'street_address_ar' => $request['street_address_ar'],
            'street_address_en' => $request['street_address_en'],
            'warehouse_id' => $request['warehouse_id']
        ]);

        $distributionCenter = new DistributionCenterResource($distributionCenter);
        $message = __('messages.store_success', ['class' => __('distribution center')]);
        $code = 201;
        return ['distributionCenter' => $distributionCenter, 'message' => $message, 'code' => $code];
    }

    public function show(DistributionCenter $distributionCenter): array
    {
        $distributionCenter = new DistributionCenterResource($distributionCenter);
        $message = __('messages.show_success', ['class' => __('distribution center')]);
        $code = 200;
        return ['distributionCenter' => $distributionCenter, 'message' => $message, 'code' => $code];
    }

    public function update($request, DistributionCenter $distributionCenter): array
    {
        $image = ImageService::update($request, $distributionCenter);
        $distributionCenter->update([
            'image' => $image,
            'name_ar' => $request['name_ar'] ?? $distributionCenter['name_ar'],
            'name_en' => $request['name_en'] ?? $distributionCenter['name_en'],
            'state_id' => $request['state_id'] ?? $distributionCenter['state_id'],
            'street_address_ar' => $request['street_address_ar'] ?? $distributionCenter['street_address_ar'],
            'street_address_en' => $request['street_address_en'] ?? $distributionCenter['street_address_en'],
            'warehouse_id' => $request['warehouse_id'] ?? $distributionCenter['warehouse_id']
        ]);

        $distributionCenter = new DistributionCenterResource($distributionCenter);
        $message = __('messages.update_success', ['class' => __('distribution center')]);
        $code = 200;
        return ['distributionCenter' => $distributionCenter, 'message' => $message, 'code' => $code];
    }

    public function continueManager(DistributionCenter $distributionCenter): array
    {
        $user = Auth::user();
        $userId = $user->getAuthIdentifier();

        $user->employee()->delete();

        $employee = $distributionCenter->employees()->create([
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

        $distributionCenter = new DistributionCenterResource($distributionCenter);
        $message = __('You are a distribution center manager now.');
        $code = 200;
        return ['data' => $distributionCenter, 'message' => $message, 'code' => $code];
    }
}

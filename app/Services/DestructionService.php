<?php

namespace App\Services;

use App\Models\Destruction;
use App\Models\StoredProduct;
use App\Models\EmployableProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\DestructionCollection;
use App\Http\Resources\DestructionResource;

class DestructionService
{
    public function index(): array
    {
        $destructions = Auth::user()->destructions()
            ->orderBy('id', 'Desc')->paginate();

        $destructions = new DestructionCollection($destructions);
        $message = __('messages.index_success', ['class' => __('destructions')]);
        $code = 200;
        return ['data' => $destructions, 'message' => $message, 'code' => $code];
    }

    public function store($request, StoredProduct $storedProduct): array
    {
        $destruction = DB::transaction(function () use ($request, $storedProduct): Destruction {
            $productDetail = $storedProduct->product;

            $destruction = Destruction::create([
                'destructionable_type' => $storedProduct->storable_type,
                'destructionable_id' => $storedProduct->storable_id,
                'product_id' => $productDetail->id,
                'expiration_date' => $storedProduct->expiration_date,
                'quantity' => $request['quantity'],
                'cause_id' => $request['cause_id'],
                'user_id' => Auth::user()->getAuthIdentifier()
            ]);

            $storedProduct->decrement('valid_quantity', $request['quantity']);

            EmployableProduct::query()
                ->where('employable_type', $storedProduct->storable_type)
                ->where('employable_id', $storedProduct->storable_id)
                ->where('product_id', $productDetail->id)
                ->firstOrCreate([
                    'employable_type' => $storedProduct->storable_type,
                    'employable_id' => $storedProduct->storable_id,
                    'product_id' => $productDetail->id,
                ])
                ->decrement('total_quantity', $request['quantity']);

            return $destruction;
        });

        $destruction = new DestructionResource($destruction);
        $message = __('messages.store_success', ['class' => __('destruction')]);
        $code = 201;
        return ['data' => $destruction, 'message' => $message, 'code' => $code];
    }

    public function show(Destruction $destruction): array
    {
        $destruction = new DestructionResource($destruction);
        $message = __('messages.show_success', ['class' => __('destruction')]);
        $code = 200;
        return ['data' => $destruction, 'message' => $message, 'code' => $code];
    }
}

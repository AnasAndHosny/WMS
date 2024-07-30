<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\SalesPorduct;
use App\Models\StoredProduct;
use App\Models\EmployableProduct;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\SaleResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\SaleCollection;
use App\Queries\SalesListQuery;

class SaleService
{
    public function index($request): array
    {
        $sales = new SalesListQuery(Auth::user()->sales(), $request);

        $sales = new SaleCollection($sales->paginate());
        $message = __('messages.index_success', ['class' => __('sales')]);
        $code = 200;
        return ['data' => $sales, 'message' => $message, 'code' => $code];
    }

    public function store($request): array
    {
        $sale = DB::transaction(function () use ($request): Sale {
            $employable = Auth::user()->employee->employable;
            $salableType = get_class($employable);
            $salableId = $employable->id;

            // Calculate the total price and prepare saled products
            $totalPrice = 0;
            $saledProducts = [];
            foreach ($request['products'] as $product) {
                $storedProduct = StoredProduct::find($product['id']);
                $productDetail = $storedProduct->product;

                $saledProducts[] = new SalesPorduct([
                    'product_id' => $productDetail->id,
                    'expiration_date' => $storedProduct->expiration_date,
                    'quantity' => $product['quantity'],
                    'price' => $productDetail->price,
                ]);

                $totalPrice += $productDetail->price * $product['quantity'];
                $storedProduct->decrement('valid_quantity', $product['quantity']);

                EmployableProduct::query()
                        ->where('employable_type', $salableType)
                        ->where('employable_id', $salableId)
                        ->where('product_id', $productDetail->id)
                        ->firstOrCreate([
                            'employable_type' => $salableType,
                            'employable_id' => $salableId,
                            'product_id' => $productDetail->id,
                        ])
                        ->decrement('total_quantity', $product['quantity']);
            }

            $totalPrice = round($totalPrice, 2);

            // Create and save the sale
            $sale = Sale::query()->create([
                'salable_type' => $salableType,
                'salable_id' => $salableId,
                'buyer_name' => $request['buyer_name'],
                'total_price' => $totalPrice,
                'user_id' => Auth::user()->getAuthIdentifier()
            ]);

            // Attach ordered products to the order
            $sale->salesProducts()->saveMany($saledProducts);

            return $sale;
        });

        $sale = new SaleResource($sale->load('salesProducts'));
        $message = __('messages.store_success', ['class' => __('sale')]);
        $code = 201;
        return ['data' => $sale, 'message' => $message, 'code' => $code];
    }

    public function show(Sale $sale): array
    {
        $sale = new SaleResource($sale->load('salesProducts'));
        $message = __('messages.show_success', ['class' => __('sale')]);
        $code = 200;
        return ['data' => $sale, 'message' => $message, 'code' => $code];
    }
}

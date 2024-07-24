<?php

namespace App\Services;

use App\Models\Warehouse;
use App\Models\StoredProduct;
use Illuminate\Support\Facades\Auth;
use App\Queries\StoredProductsListQuery;
use App\Http\Resources\StoredProductResource;
use App\Http\Resources\StoredProductCollection;

class StoredProductService
{
    public function index($request): array
    {
        $products = new StoredProductsListQuery(
            Auth::user()->employee->employable
                ->storedProducts()
                ->where('valid_quantity', '!=', 0),
            $request
        );

        $products = new StoredProductCollection($products->paginate());
        $message = __('messages.index_success', ['class' => __('products')]);
        $code = 200;
        return ['data' => $products, 'message' => $message, 'code' => $code];
    }

    public function warehousesProductList($request, Warehouse $warehouse): array
    {
        $products = new StoredProductsListQuery(
            $warehouse->storedProducts()->active(),
            $request,
            false
        );

        $products = new StoredProductCollection($products->paginate());
        $message = __('messages.index_success', ['class' => __('products')]);
        $code = 200;
        return ['data' => $products, 'message' => $message, 'code' => $code];
    }

    public function warehouseProductList($request): array
    {
        $warehouse = Auth::user()->employee->employable->warehouse;

        $products = new StoredProductsListQuery(
            $warehouse->storedProducts()->active(),
            $request,
            false
        );

        $products = new StoredProductCollection($products->paginate());
        $message = __('messages.index_success', ['class' => __('products')]);
        $code = 200;
        return ['data' => $products, 'message' => $message, 'code' => $code];
    }

    public function show(StoredProduct $product): array
    {
        $product = new StoredProductResource($product);
        $message = __('messages.show_success', ['class' => __('product')]);
        $code = 200;
        return ['data' => $product, 'message' => $message, 'code' => $code];
    }

    public function update($request, StoredProduct $product): array
    {
        $product->update([
            'active' => $request['active'] ?? $product['active'],
            'max' => $request['max'] ?? $product['max'],
        ]);

        $product = new StoredProductResource($product);
        $message = __('messages.update_success', ['class' => __('product')]);
        $code = 200;
        return ['data' => $product, 'message' => $message, 'code' => $code];
    }
}

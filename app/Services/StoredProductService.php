<?php

namespace App\Services;

use App\Http\Resources\StoredProductCollection;
use App\Http\Resources\StoredProductResource;
use App\Models\StoredProduct;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Auth;

class StoredProductService
{
    public function index(): array
    {
        $products = Auth::user()->employee->employable->storedProducts()->paginate();
        $products = new StoredProductCollection($products);
        $message = __('messages.index_success', ['class' => __('products')]);
        $code = 200;
        return ['data' => $products, 'message' => $message, 'code' => $code];
    }

    public function warehouseProductList(Warehouse $warehouse): array
    {
        $products = $warehouse->storedProducts()->active()->paginate();
        $products = new StoredProductCollection($products);
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

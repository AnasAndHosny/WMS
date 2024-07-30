<?php

namespace App\Services;

use App\Http\Resources\ProductCollection;
use App\Http\Resources\SubCategoryResource;
use App\Models\Product;
use App\Models\SubCategory;
use App\Queries\SubCategoriesListQuery;

class SubCategoryService
{
    public function index($request): array
    {
        $category = new SubCategoriesListQuery(SubCategory::query(), $request);
        $category = SubCategoryResource::collection($category->get());
        $message = __('messages.index_success', ['class' => __('Categories')]);
        $code = 200;
        return ['category' => $category, 'message' => $message, 'code' => $code];
    }

    public function store($request): array
    {
        $image = ImageService::store($request);
        $category = SubCategory::query()->create([
            'image' => $image,
            'name_ar' => $request['name_ar'],
            'name_en' => $request['name_en'],
            'category_id' => $request['category_id']
        ]);

        $category = new SubCategoryResource($category);
        $message = __('messages.store_success', ['class' => __('category')]);
        $code = 201;
        return ['category' => $category, 'message' => $message, 'code' => $code];
    }

    public function show(SubCategory $category): array
    {
        $category = new SubCategoryResource($category);
        $message = __('messages.show_success', ['class' => __('category')]);
        $code = 200;
        return ['category' => $category, 'message' => $message, 'code' => $code];
    }

    public function update($request, SubCategory $category): array
    {
        $image = ImageService::update($request, $category);
        $category->update([
            'image' => $image,
            'name_ar' => $request['name_ar'] ?? $category['name_ar'],
            'name_en' => $request['name_en'] ?? $category['name_en'],
            'category_id' => $request['category_id'] ?? $category['category_id']
        ]);

        $category = new SubCategoryResource($category);
        $message = __('messages.update_success', ['class' => __('category')]);
        $code = 200;
        return ['category' => $category, 'message' => $message, 'code' => $code];
    }

    public function productsList(SubCategory $category): array
    {
        $product = Product::where('subcategory_id', $category->id)->paginate();
        $product = new ProductCollection($product);
        $message = __('messages.index_success', ['class' => __('products')]);
        $code = 200;
        return ['product' => $product, 'message' => $message, 'code' => $code];
    }

    public function destroy(SubCategory $category): array
    {
        //
    }
}

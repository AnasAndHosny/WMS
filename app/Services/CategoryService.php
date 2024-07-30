<?php

namespace App\Services;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\SubCategoryCollection;
use App\Models\Category;
use App\Models\SubCategory;
use App\Queries\CategoriesListQuery;
use App\Queries\SubCategoriesListQuery;

class CategoryService
{
    public function index($request): array
    {
        $category = new CategoriesListQuery(Category::query(), $request);
        $category = CategoryResource::collection($category->get());
        $message = __('messages.index_success', ['class' => __('Categories')]);
        $code = 200;
        return ['category' => $category, 'message' => $message, 'code' => $code];
    }

    public function store($request): array
    {
        $category = Category::query()->create([
            'name_ar' => $request['name_ar'],
            'name_en' => $request['name_en']
        ]);

        $category = new CategoryResource($category);
        $message = __('messages.store_success', ['class' => __('category')]);
        $code = 201;
        return ['category' => $category, 'message' => $message, 'code' => $code];
    }

    public function show(Category $category): array
    {
        $category = new CategoryResource($category);
        $message = __('messages.show_success', ['class' => __('category')]);
        $code = 200;
        return ['category' => $category, 'message' => $message, 'code' => $code];
    }

    public function update($request, Category $category): array
    {
        $category->update([
            'name_ar' => $request['name_ar'] ?? $category['name_ar'],
            'name_en' => $request['name_en'] ?? $category['name_en']
        ]);
        $category = new CategoryResource($category);

        $message = __('messages.update_success', ['class' => __('category')]);
        $code = 200;
        return ['category' => $category, 'message' => $message, 'code' => $code];
    }

    public function subCategoriesList($request, Category $category): array
    {
        $category = SubCategory::where('category_id', $category->id);
        $category = new SubCategoriesListQuery($category, $request);
        $category = new SubCategoryCollection($category->paginate());
        $message = __('messages.index_success', ['class' => __('Categories')]);
        $code = 200;
        return ['category' => $category, 'message' => $message, 'code' => $code];
    }

    public function destroy(Category $category): array
    {
        $category->delete();

        $message = __('messages.update_success', ['class' => __('category')]);
        $code = 204;
        return ['category' => $category, 'message' => $message, 'code' => $code];
    }
}

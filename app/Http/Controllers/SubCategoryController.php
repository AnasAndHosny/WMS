<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubCategory\StoreSubCategoryRequest;
use App\Http\Requests\SubCategory\UpdateSubCategoryRequest;
use App\Http\Responses\Response;
use App\Models\SubCategory;
use App\Services\SubCategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class SubCategoryController extends Controller
{
    private SubCategoryService $subCategoryService;

    public function __construct(SubCategoryService $subCategoryService)
    {
        $this->subCategoryService = $subCategoryService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $data = [];
        try {
            $data = $this->subCategoryService->index($request);
            return Response::Success($data['category'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubCategoryRequest $request): JsonResponse
    {
        $data = [];
        try {
            $data = $this->subCategoryService->store($request);
            return Response::Success($data['category'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SubCategory $category): JsonResponse
    {
        $data = [];
        try {
            $data = $this->subCategoryService->show($category);
            return Response::Success($data['category'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubCategoryRequest $request, SubCategory $category): JsonResponse
    {
        $data = [];
        try {
            $data = $this->subCategoryService->update($request, $category);
            return Response::Success($data['category'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    /**
     * Display a listing of the sub-categories from specific Category.
     */
    public function productsList(SubCategory $category): JsonResponse
    {
        $data = [];
        try {
            $data = $this->subCategoryService->productsList($category);
            return Response::Success($data['product'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubCategory $category): JsonResponse
    {
        $data = [];
        try {
            $data = $this->subCategoryService->destroy($category);
            return Response::Success($data['category'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }
}

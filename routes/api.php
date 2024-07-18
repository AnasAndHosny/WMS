<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\ManufacturerController;
use App\Http\Controllers\StoredProductController;
use App\Http\Controllers\ShippingCompanyController;
use App\Http\Controllers\DistributionCenterController;
use App\Http\Controllers\SaleController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::get('logout', 'logout')->middleware('auth:sanctum');
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('user')->controller(AuthController::class)->group(function () {
        Route::get('profile', 'showProfile');
        Route::patch('profile', 'updateProfile');
    });

    Route::prefix('categories')->controller(CategoryController::class)->group(function () {
        Route::get('/', 'index')->middleware('can:category.index');
        Route::post('/', 'store')->middleware('can:category.store');
        Route::get('{category}', 'show')->middleware('can:category.show');
        Route::patch('{category}', 'update')->middleware('can:category.update');
        Route::get('{category}/subcategories', 'subCategoriesList')->middleware('can:category.index');
        Route::delete('{category}', 'destroy')->middleware('can:category.destroy');
    });

    Route::prefix('subcategories')->controller(SubCategoryController::class)->group(function () {
        Route::get('/', 'index')->middleware('can:category.index');
        Route::post('/', 'store')->middleware('can:category.store');
        Route::get('{category}', 'show')->middleware('can:category.show');
        Route::patch('{category}', 'update')->middleware('can:category.update');
        Route::get('{category}/products', 'productsList')->middleware('can:product.index');
        Route::delete('{category}', 'destroy')->middleware('can:category.destroy');
    });

    Route::prefix('warehouses')->controller(WarehouseController::class)->group(function () {
        Route::get('/', 'index')->middleware('can:warehouse.index');
        Route::post('/', 'store')->middleware('can:warehouse.store');
        Route::get('distribution-centers', 'showDistributionCenters')->middleware('can:warehouse.show.centers');
        Route::get('{warehouse}', 'show')->middleware('can:warehouse.show');
        Route::patch('{warehouse}', 'update')->middleware('can:warehouse.update');
    });

    Route::prefix('distribution-center')->controller(DistributionCenterController::class)->group(function () {
        Route::get('/', 'index')->middleware('can:distributionCenter.index');
        Route::post('/', 'store')->middleware('can:distributionCenter.store');
        Route::get('{distributionCenter}', 'show')->middleware('can:distributionCenter.show');
        Route::patch('{distributionCenter}', 'update')->middleware('can:distributionCenter.update');
    });

    Route::prefix('products')->controller(ProductController::class)->group(function () {
        Route::get('/', 'index')->middleware('can:product.index');
        Route::post('/', 'store')->middleware('can:product.store');
        Route::get('{product}', 'show')->middleware('can:product.show');
        Route::patch('{product}', 'update')->middleware('can:product.update');
        Route::patch('{product}/min', 'updateMinQuantity');
    });

    Route::prefix('employees')->controller(EmployeeController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('profile', 'showProfile');
        Route::patch('profile', 'updateProfile');
        Route::get('{employee}', 'show');
        Route::patch('{employee}', 'update');
    });

    Route::prefix('manufacturer')->controller(ManufacturerController::class)->group(function () {
        Route::get('/', 'index')->middleware('can:manufacturer.index');
        Route::post('/', 'store')->middleware('can:manufacturer.store');
        Route::get('{manufacturer}', 'show')->middleware('can:manufacturer.show');
        Route::patch('{manufacturer}', 'update')->middleware('can:manufacturer.update');
    });

    Route::prefix('stored-products')->controller(StoredProductController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('warehouse/{warehouse}', 'warehouseProductList');
        Route::get('{storedProduct}', 'show');
        Route::patch('{storedProduct}', 'update');
    });

    Route::prefix('orders')->controller(OrderController::class)->group(function () {
        Route::get('/buy', 'buyOrdersList');
        Route::get('/sell', 'sellOrdersList');
        Route::get('/manufacturer', 'manufacturerOrdersList');
        Route::post('/warhouse', 'storeWarehouseOrder');
        Route::post('/manufacturer', 'storeManufacturerOrder');
        Route::get('{order}', 'show');
        Route::put('{order}', 'updateManufacturerOrder');
        Route::get('{order}/accept', 'accept');
        Route::delete('{order}/reject', 'reject');
        Route::get('{order}/receive', 'receive');
        Route::delete('{order}/delete', 'delete');
    });

    Route::prefix('shipping-companies')->controller(ShippingCompanyController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('{shippingCompany}', 'show');
        Route::patch('{shippingCompany}', 'update');
    });

    Route::prefix('shipments')->controller(ShipmentController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('{shipment}', 'show');
        Route::patch('{shipment}', 'update');
    });

    Route::prefix('sales')->controller(SaleController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('{sale}', 'show');
    });
});

Route::prefix('cities')->controller(CityController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('{city}/states', 'statesList');
});

Route::prefix('states')->controller(StateController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('{state}', 'show');
    Route::patch('{state}', 'update');
});

Route::prefix('roles')->controller(RoleController::class)->group(function () {
    Route::get('warehouse', 'warehouseRolesList');
    Route::get('distribution-center', 'distributionCenterRolesList');
});

<?php

use App\Events\Test;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ShipmentController;
use Illuminate\Support\Facades\Notification;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\DestructionController;
use App\Http\Controllers\SubCategoryController;
use App\Events\ProductQuantityWarningNotifyUser;
use App\Http\Controllers\ManufacturerController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\StoredProductController;
use App\Http\Controllers\ShippingCompanyController;
use App\Http\Controllers\DestructionCauseController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\DistributionCenterController;
use App\Http\Controllers\Auth\ForgetPasswordController;
use App\Notifications\ProductQuantityWarningNotification;
use App\Http\Controllers\Auth\EmailVerificationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::get('logout', 'logout')->middleware('auth:sanctum');
});

Route::prefix('otp')->group(function () {
    Route::get('email-verification', [EmailVerificationController::class, 'sendEmailVerification'])->middleware('auth:sanctum');
    Route::post('email-verification', [EmailVerificationController::class, 'emailVerification'])->middleware('auth:sanctum');
    Route::post('password/forget-password', [ForgetPasswordController::class, 'forgetPassword']);
    Route::post('password/reset', [ResetPasswordController::class, 'passwordReset']);
    Route::post('check', [OtpController::class, 'check']);
});

Route::middleware(['auth:sanctum', 'user.banned', 'user.verified'])->group(function () {
    Route::prefix('user')->controller(AuthController::class)->group(function () {
        Route::get('profile', 'showProfile');
        Route::patch('profile', 'updateProfile');
        Route::get('back-admin', 'backAdmin')->middleware('can:manager.continue');
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
        Route::get('distribution-centers', 'showDistributionCenters')->middleware('can:warehouse.centers.index');
        Route::get('{warehouse}', 'show')->middleware('can:warehouse.show');
        Route::patch('{warehouse}', 'update')->middleware('can:warehouse.update');
        Route::get('{warehouse}/continue', 'continueManager')->middleware('can:manager.continue');
    });

    Route::prefix('distribution-center')->controller(DistributionCenterController::class)->group(function () {
        Route::get('/', 'index')->middleware('can:center.index');
        Route::post('/', 'store')->middleware('can:center.store');
        Route::get('{distributionCenter}', 'show')->middleware('can:view,distributionCenter');
        Route::patch('{distributionCenter}', 'update')->middleware('can:center.update');
        Route::get('{distributionCenter}/continue', 'continueManager')->middleware('can:manager.continue');
    });

    Route::prefix('products')->controller(ProductController::class)->group(function () {
        Route::get('/', 'index')->middleware('can:product.index');
        Route::post('/', 'store')->middleware('can:product.store');
        Route::get('{product}', 'show')->middleware('can:product.show');
        Route::patch('{product}', 'update')->middleware('can:product.update');
        Route::patch('{product}/min', 'updateMinQuantity')->middleware('can:product.min.update');
    });

    Route::prefix('employees')->controller(EmployeeController::class)->group(function () {
        Route::get('/', 'index')->middleware('can:employee.index');
        Route::post('/', 'store')->middleware('can:employee.store');
        Route::get('profile', 'showProfile');
        Route::patch('profile', 'updateProfile');
        Route::get('{employee}', 'show')->middleware('can:employee.show');
        Route::patch('{employee}', 'update')->middleware('can:employee.update');
        Route::get('{employee}/ban', 'ban')->middleware('can:employee.ban');
        Route::delete('{employee}/ban', 'unban')->middleware('can:employee.ban');
    });

    Route::prefix('manufacturer')->controller(ManufacturerController::class)->group(function () {
        Route::get('/', 'index')->middleware('can:manufacturer.index');
        Route::post('/', 'store')->middleware('can:manufacturer.store');
        Route::get('{manufacturer}', 'show')->middleware('can:manufacturer.show');
        Route::patch('{manufacturer}', 'update')->middleware('can:manufacturer.update');
    });

    Route::prefix('stored-products')->controller(StoredProductController::class)->group(function () {
        Route::get('/', 'index')->middleware('can:product.index');
        Route::get('warehouse', 'warehouseProductList')->middleware('can:warehouse.product.index');
        Route::get('warehouse/{warehouse}', 'warehousesProductList')->middleware('can:warehouses.product.index');
        Route::get('{storedProduct}', 'show')->middleware('can:view,storedProduct');
        Route::patch('{storedProduct}', 'update')->middleware('can:update,storedProduct');
        Route::post('{storedProduct}/destruction', [DestructionController::class, 'store'])->middleware('can:destruct,storedProduct');
    });

    Route::prefix('orders')->controller(OrderController::class)->group(function () {
        Route::get('/buy', 'buyOrdersList')->middleware('can:orders.index');
        Route::get('/sell', 'sellOrdersList')->middleware('can:orders.index');
        Route::get('/manufacturer', 'manufacturerOrdersList')->middleware('can:orders.index');
        Route::post('/warhouse', 'storeWarehouseOrder')->middleware('can:orders.buy.store');
        Route::post('/manufacturer', 'storeManufacturerOrder')->middleware('can:orders.manufacturer.store');
        Route::get('{order}', 'show')->middleware('can:view,order');
        Route::put('{order}', 'updateManufacturerOrder')->middleware('can:updateManufacturer,order');
        Route::get('{order}/accept', 'accept')->middleware('can:updateSell,order');
        Route::delete('{order}/reject', 'reject')->middleware('can:updateSell,order');
        Route::get('{order}/receive', 'receive')->middleware('can:updateBuy,order');
        Route::delete('{order}/delete', 'delete')->middleware('can:updateBuy,order');
    });

    Route::prefix('shipping-companies')->controller(ShippingCompanyController::class)->group(function () {
        Route::get('/', 'index')->middleware('can:shippingCompany.index');
        Route::post('/', 'store')->middleware('can:shippingCompany.store');
        Route::get('{shippingCompany}', 'show')->middleware('can:shippingCompany.show');
        Route::patch('{shippingCompany}', 'update')->middleware('can:shippingCompany.update');
    });

    Route::prefix('shipments')->controller(ShipmentController::class)->group(function () {
        Route::get('/', 'index')->middleware('can:shipment.index');
        Route::post('/', 'store')->middleware('can:shipment.store');
        Route::get('{shipment}', 'show')->middleware('can:view,shipment');
    });

    Route::prefix('sales')->controller(SaleController::class)->group(function () {
        Route::get('/', 'index')->middleware('can:sale.index');
        Route::post('/', 'store')->middleware('can:sale.store');
        Route::get('{sale}', 'show')->middleware('can:view,sale');
    });

    Route::prefix('roles')->controller(RoleController::class)->group(function () {
        Route::get('warehouse', 'warehouseRolesList')->middleware('can:role.index');
        Route::get('distribution-center', 'distributionCenterRolesList')->middleware('can:role.index');
        Route::post('/', 'store')->middleware('can:role.store');
    });

    Route::prefix('destruction')->controller(DestructionController::class)->group(function () {
        Route::get('/', 'index')->middleware('can:destruction.index');
        Route::get('{destruction}', 'show')->middleware('can:view,destruction');
    });

    Route::prefix('reports')->controller(ReportController::class)->group(function () {
        Route::post('order', 'orderReport')->middleware('can:report.show');
        Route::post('order/excel', 'orderReportExcel')->middleware('can:report.show');
        Route::post('order/pdf', 'orderReportPdf')->middleware('can:report.show');
        Route::post('product', 'productReport')->middleware('can:report.show');
        Route::post('product/excel', 'productReportExcel')->middleware('can:report.show');
        Route::post('product/pdf', 'productReportPdf')->middleware('can:report.show');
        Route::post('product/{product}', 'specificProductReport')->middleware('can:report.show');
        Route::post('product/{product}/excel', 'specificProductReportExcel')->middleware('can:report.show');
        Route::post('product/{product}/pdf', 'specificProductReportPdf')->middleware('can:report.show');
    });

    Route::prefix('dashboard')->controller(DashboardController::class)->group(function () {
        Route::get('/', 'index')->middleware('can:report.show');
    });

    Route::prefix('notifications')->controller(NotificationController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('mark-all', 'markAllAsRead');
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

Route::prefix('destruction-causes')->controller(DestructionCauseController::class)->group(function () {
    Route::get('/', 'index');
});

Route::get('/test/realtime', function () {
    return event(new ProductQuantityWarningNotifyUser(Product::find(1), User::find(2)));

    return event(new Test());
});

Route::get('/test/notifications', function () {
    return Notification::send(User::all(), new ProductQuantityWarningNotification(Product::find(1)));
});

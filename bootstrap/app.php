<?php

use App\Models\City;
use App\Models\User;
use App\Models\Order;
use App\Models\State;
use App\Models\Product;
use App\Models\Category;
use App\Models\Employee;
use App\Models\Warehouse;
use App\Models\SubCategory;
use App\Models\Manufacturer;
use App\Models\StoredProduct;
use App\Http\Responses\Response;
use App\Models\DistributionCenter;
use App\Models\Shipment;
use App\Models\ShippingCompany;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);

        $middleware->api(prepend: [
            \App\Http\Middleware\Localization::class, //change locale language for api routes
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // handle NotAuthorized exceptions from api requests and send JsonResponse
        $exceptions->render(function (AccessDeniedHttpException $e, $request) {
            if ($request->is('api/*')) {
                return Response::Error([], __('messages.notAuthorized'), 403);
            }
        });

        // handle route model binding exceptions from api requests and send JsonResponse
        $exceptions->render(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/*') && ($e->getPrevious() instanceof ModelNotFoundException)) {
                $class = match ($e->getPrevious()->getModel()) {
                    User::class => 'user',
                    Category::class, SubCategory::class => 'category',
                    City::class  => 'city',
                    State::class => 'state',
                    Warehouse::class => 'warehouse',
                    DistributionCenter::class => 'distribution center',
                    Product::class, StoredProduct::class => 'product',
                    Employee::class => 'employee',
                    Manufacturer::class => 'manufacturer',
                    Order::class => 'order',
                    ShippingCompany::class => 'shipping company',
                    Shipment::class => 'shipment',
                    default => 'record'
                };

                return Response::Error([], __('messages.notFound', ['class' => __($class)]), 404);
            }
        });
    })->create();

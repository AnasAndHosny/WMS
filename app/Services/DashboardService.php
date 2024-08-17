<?php

namespace App\Services;

use App\Models\EmployableProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Resources\ProductResource;
use App\Models\Destruction;
use App\Models\StoredProduct;

class DashboardService
{
    private ReportService $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index(): array
    {
        $dashboard['top_selling_products'] = $this->topSellingProducts();

        $dashboard['products_report'] = $this->productsReport();

        $dashboard['sell_orders_report'] = $this->ordersReport();

        return [
            'data' => $dashboard,
            'message' => __('messages.show_success', ['class' => __('dashboard')]),
            'code' => 200
        ];
    }

    private function topSellingProducts(): array
    {
        $topSellingProducts = [];

        $productReportRequest = new Request([]);

        $productReport = $this->reportService->ProductReport($productReportRequest);

        $productReportCollect = collect($productReport['data']['report'])->sortByDesc('quantity_sold');

        $count = 0;
        foreach ($productReportCollect as $key => $value) {
            if ($count == 5) break;

            $topSellingProducts[] = [
                'product' => new ProductResource(Product::where('name_' . App::getLocale(), $value['product_name'])->first()),
                'quantity' => $value['quantity_sold'],
            ];

            $count++;
        }

        return $topSellingProducts;
    }

    private function productsReport(): array
    {
        $productsReport = [
            'low_stock_products' => 0,
            'valid_quantity' => 0,
            'expired_quantity' => 0,
            'quantity_disposed' => 0,
        ];

        // Get the authenticated employee and their employable type and ID
        $employee = auth()->user()->employee;
        $employableType = $employee ? get_class($employee->employable) : null;
        $employableId = $employee ? $employee->employable->id : null;

        $productsReport['low_stock_products'] = EmployableProduct::query()
            ->whereColumn('total_quantity', '<', 'min_quantity')
            ->when($employableType, function ($query) use ($employableType, $employableId) {
                $query->where('employable_type', $employableType)
                    ->where('employable_id', $employableId);
            })
            ->count();

        $productsReport['valid_quantity'] = StoredProduct::valid()
            ->when($employableType, function ($query) use ($employableType, $employableId) {
                $query->where('storable_type', $employableType)
                    ->where('storable_id', $employableId);
            })
            ->sum('valid_quantity');

        $productsReport['expired_quantity'] = StoredProduct::expired()
            ->when($employableType, function ($query) use ($employableType, $employableId) {
                $query->where('storable_type', $employableType)
                    ->where('storable_id', $employableId);
            })
            ->sum('valid_quantity');

        $productsReport['quantity_disposed'] = Destruction::query()
            ->when($employableType, function ($query) use ($employableType, $employableId) {
                $query->where('destructionable_type', $employableType)
                    ->where('destructionable_id', $employableId);
            })
            ->sum('quantity');

        return $productsReport;
    }

    private function ordersReport(): array
    {;
        $orderReportRequest = new Request([
            'frequency' => 'yearly',
            'type' => 'sell',
        ]);

        $periodicOrdersReport = $this->reportService->orderReport($orderReportRequest);
        $totalOrdersReport = last($periodicOrdersReport['data']);

        return $totalOrdersReport;
    }
}

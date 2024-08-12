<?php

namespace App\Services;

use App\Http\Resources\ProductResource;
use App\Models\Destruction;
use App\Models\Manufacturer;
use App\Models\Order;
use App\Models\OrderedProduct;
use App\Models\OrderStatus;
use App\Models\Product;
use App\Models\SalesPorduct;
use App\Models\StoredProduct;
use Carbon\Carbon;

class ReportService
{
    private $orderStatuses = [];

    public function __construct()
    {
        $orderStatuses = OrderStatus::all();
        foreach ($orderStatuses as $orderStatus) {
            $this->orderStatuses[$orderStatus->id] = $orderStatus->name_en;
        }
    }

    /**
     * Generate a report of orders based on the request parameters.
     *
     * @param $request
     * @return array
     */
    public function orderReport($request): array
    {
        $report = [];

        // Get the authenticated employee
        $employee = auth()->user()->employee;

        // If an employee is authenticated, get their orders; otherwise, get all orders excluding manufacturers in sell orders
        $ordersQuery = $request->type == 'buy'
            ? (
                $employee
                ? $employee->employable()->buyOrders()
                : Order::query()
            )
            : (
                $employee
                ? $employee->employable()->sellOrders()
                : Order::where('orderable_from_type', '!=', Manufacturer::class)
            );

        $firstOrderDate = Carbon::parse(
            (clone $ordersQuery)->orderBy('created_at')
                ->first()->created_at  ?? now()
        )->startOfDay();

        $today = Carbon::now()->endOfDay();

        $fromDate = Carbon::parse($request->input('start_date'))->startOfDay();
        $toDate = Carbon::parse($request->input('end_date'))->endOfDay();

        // Define start and end dates from the request or defaults
        $startDate = $request->has('start_date')
            ? (
                $fromDate->lt($firstOrderDate) ? $firstOrderDate : $fromDate
            )
            : $firstOrderDate;
        $endDate = $request->has('end_date')
            ? (
                $toDate->gt($today) ? $today : $toDate
            )
            : $today;

        $total = [
            'from' => $startDate->toDateString(),
            "to" => $endDate->toDateString(),
            'new_orders' => 0,
        ];

        foreach ($this->orderStatuses as $status) {
            $total[strtolower(str_replace(' ', '_', $status)) . '_orders'] = 0;
        }
        $total['cost'] = 0;

        // Define the reporting frequency: daily, weekly, monthly, or yearly
        $frequency = $request->input('frequency', 'monthly');

        // Loop through each period within the date range
        while ($startDate->lt($endDate)) {
            // Calculate the end date of the current reporting period based on the frequency
            $toDate = $this->calculateEndDate($startDate, $frequency, $endDate);

            // Clone the query builder and apply the date filter for the current period
            $periodicReports = (clone $ordersQuery)
                ->whereBetween('created_at', [$startDate, $toDate])
                ->get();

            // Gather counts for different order statuses within the current period
            $report[] = $this->generateOrderReportEntry($periodicReports, $startDate, $toDate, $total);

            // Move to the next period
            $startDate = $this->calculateNextStartDate($startDate, $frequency);
        }

        $total['cost'] = round($total['cost'], 2);
        $report[] = $total;

        // Return the report with a success message and status code
        return [
            'data' => $report,
            'message' => __('messages.index_success', ['class' => __('Orders')]),
            'code' => 200
        ];
    }

    /**
     * Generate a report of products based on the request parameters.
     *
     * @param $request
     * @return array
     */
    public function ProductReport($request): array
    {

        // Get the authenticated employee and their employable type and ID
        $employee = auth()->user()->employee;
        $employableType = $employee ? get_class($employee->employable) : null;
        $employableId = $employee ? $employee->employable->id : null;

        $today = Carbon::now()->endOfDay();

        $fromDate = Carbon::parse($request->input('start_date'))->startOfDay();
        $toDate = Carbon::parse($request->input('end_date'))->endOfDay();

        $startDate = $request->has('start_date')
            ? $fromDate
            : null;
        $endDate = $request->has('end_date')
            ? (
                $toDate->gt($today) ? $today : $toDate
            )
            : $today;

        // Get all products
        $products = Product::all();

        // Initialize an array to hold the ordered products data
        $report = [];

        // Iterate through each product to gather report data
        foreach ($products as $product) {
            $reportEntry = [
                'product_name' => $product->name,
                'product_image' => $product->image,
                'quantity_ordered_to_sell' => 0,
                'quantity_sold' => 0,
                'quantity_disposed' => 0,
                'quantity_expired' => 0,
                'quantity_purchased' => 0,
                'revenue' => 0,
                'cost' => 0,
            ];

            $productId = $product->id;

            // Get ordered products for the current product
            $sellOrderProducts = OrderedProduct::with('order')
                ->where('product_id', $productId)
                ->whereHas('order', function ($query) use ($employableType, $employableId, $startDate, $endDate) {
                    if ($employableType) {
                        $query->where('orderable_from_type', $employableType)
                            ->where('orderable_from_id', $employableId);
                    } else {
                        $query->where('orderable_from_type', '!=', Manufacturer::class);
                    }
                    $query->when($startDate)
                        ->whereDate('created_at', '>', $startDate);
                    $query->whereDate('created_at', '<', $endDate);
                })->get();

            // Calculate ordered and delivered quantities
            $sellOrderQuantity = $sellOrderProducts->sum('quantity');
            $reportEntry['quantity_ordered_to_sell'] = $sellOrderQuantity;

            $deliveredProducts = $sellOrderProducts->where('order.status_id', array_search('Delivered', $this->orderStatuses));

            foreach ($deliveredProducts as $product) {
                $reportEntry['quantity_sold'] += $product->quantity;
                $reportEntry['revenue'] += $product->cost * $product->quantity;
            };

            $boughtProducts = OrderedProduct::with('order')
                ->where('product_id', $productId)
                ->whereHas('order', function ($query) use ($employableType, $employableId, $startDate, $endDate) {
                    if ($employableType) {
                        $query->where('orderable_by_type', $employableType)
                            ->where('orderable_by_id', $employableId);
                    }
                    $query->where('status_id', array_search('Delivered', $this->orderStatuses));
                    $query->when($startDate)
                        ->whereDate('created_at', '>', $startDate);
                    $query->whereDate('created_at', '<', $endDate);
                })->get();

            foreach ($boughtProducts as $product) {
                $reportEntry['quantity_purchased'] += $product->quantity;
                $reportEntry['cost'] += $product->quantity * $product->cost;
            }

            // Get destruction data for the current product
            $destructedProducts = Destruction::where('product_id', $productId)
                ->when($startDate)
                ->whereDate('created_at', '>', $startDate)
                ->whereDate('created_at', '<', $endDate);
            if ($employableType) {
                $destructedProducts->where('destructionable_type', $employableType)
                    ->where('destructionable_id', $employableId);
            }
            $destructedQuantity = $destructedProducts->sum('quantity'); //->sum('quantity');
            $reportEntry['quantity_disposed'] = $destructedQuantity;

            $expiredQuantity = StoredProduct::query()
                ->where('product_id', $productId)
                ->where('expiration_date', '!=', null)
                ->whereDate('expiration_date', '<', $startDate ?: now()->startOfDay())
                ->when($employableType, function ($query) use ($employableType, $employableId) {
                    $query->where('orderable_by_type', $employableType)
                        ->where('orderable_by_id', $employableId);
                })->sum('expired_quantity');

            $reportEntry['quantity_expired'] = $expiredQuantity;

            $soledProducts = SalesPorduct::query()
                ->where('product_id', $productId)
                ->whereHas('sale', function ($query) use ($employableType, $employableId) {
                    $query->when($employableType, function ($query) use ($employableType, $employableId) {
                        $query->where('orderable_by_type', $employableType)
                            ->where('orderable_by_id', $employableId);
                    });
                })->get();

            foreach ($soledProducts as $product) {
                $reportEntry['quantity_sold'] += $product->quantity;
                $reportEntry['revenue'] += $product->quantity * $product->price;
            }
            $reportEntry['revenue'] = round($reportEntry['revenue'], 2);
            $reportEntry['cost'] = round($reportEntry['cost'], 2);

            $report['report'][] = $reportEntry;
        }

        // Return the report with a success message and status code
        return [
            'data' => $report,
            'message' => __('messages.index_success', ['class' => __('Products')]),
            'code' => 200
        ];
    }

    public function specificProductReport($request, Product $product): array
    {
        $report = [
            'product' => new ProductResource($product),
        ];

        // Get the authenticated employee and their employable type and ID
        $employee = auth()->user()->employee;
        $employableType = $employee ? get_class($employee->employable) : null;
        $employableId = $employee ? $employee->employable->id : null;

        $firstOrderDate = Carbon::parse(
            $product->orderedProducts()
                ->when($employableType, function ($query) use ($employableType, $employableId) {
                    $query->whereHas('order', function ($query) use ($employableType, $employableId) {
                        $query->where('orderable_by_type', $employableType)
                            ->where('orderable_by_id', $employableId);
                    });
                })
                ->orderBy('created_at')
                ->first()->created_at ?? now()
        )->startOfDay();

        $today = Carbon::now()->endOfDay();

        $fromDate = Carbon::parse($request->input('start_date'))->startOfDay();
        $toDate = Carbon::parse($request->input('end_date'))->endOfDay();

        // Define start and end dates from the request or defaults
        $startDate = $request->has('start_date')
            ? (
                $fromDate->lt($firstOrderDate) ? $firstOrderDate : $fromDate
            )
            : $firstOrderDate;
        $endDate = $request->has('end_date')
            ? (
                $toDate->gt($today) ? $today : $toDate
            )
            : $today;

        // Define the reporting frequency: daily, weekly, monthly, or yearly
        $frequency = $request->input('frequency', 'monthly');

        $total = [
            'from' => $startDate->toDateString(),
            'to' => $endDate->toDateString(),
            'quantity_ordered_to_sell' => 0,
            'quantity_sold' => 0,
            'quantity_disposed' => 0,
            'quantity_expired' => 0,
            'quantity_purchased' => 0,
            'revenue' => 0,
            'cost' => 0,
        ];

        while ($startDate->lt($endDate)) {
            // Calculate the end date of the current reporting period based on the frequency
            $toDate = $this->calculateEndDate($startDate, $frequency, $endDate);

            // Add product report data to the array
            $reportEntry = [
                'from' => $startDate->toDateString(),
                'to' => $toDate->toDateString(),
                'quantity_ordered_to_sell' => 0,
                'quantity_sold' => 0,
                'quantity_disposed' => 0,
                'quantity_expired' => 0,
                'quantity_purchased' => 0,
                'revenue' => 0,
                'cost' => 0,
            ];

            $productId = $product->id;

            // Get ordered products for the current product
            $sellOrderProducts = OrderedProduct::with('order')
                ->where('product_id', $productId)
                ->whereHas('order', function ($query) use ($employableType, $employableId, $startDate, $toDate) {
                    if ($employableType) {
                        $query->where('orderable_from_type', $employableType)
                            ->where('orderable_from_id', $employableId);
                    } else {
                        $query->where('orderable_from_type', '!=', Manufacturer::class);
                    }
                    $query->when($startDate)
                        ->whereDate('created_at', '>', $startDate);
                    $query->whereDate('created_at', '<', $toDate);
                })->get();

            // Calculate ordered and delivered quantities
            $sellOrderQuantity = $sellOrderProducts->sum('quantity');
            $reportEntry['quantity_ordered_to_sell'] = $sellOrderQuantity;

            $deliveredProducts = $sellOrderProducts->where('order.status_id', array_search('Delivered', $this->orderStatuses));

            foreach ($deliveredProducts as $product) {
                $reportEntry['quantity_sold'] += $product->quantity;
                $reportEntry['revenue'] += $product->cost * $product->quantity;
            };

            $boughtProducts = OrderedProduct::with('order')
                ->where('product_id', $productId)
                ->whereHas('order', function ($query) use ($employableType, $employableId, $startDate, $toDate) {
                    if ($employableType) {
                        $query->where('orderable_by_type', $employableType)
                            ->where('orderable_by_id', $employableId);
                    }
                    $query->where('status_id', array_search('Delivered', $this->orderStatuses));
                    $query->when($startDate)
                        ->whereDate('created_at', '>', $startDate);
                    $query->whereDate('created_at', '<', $toDate);
                })->get();

            foreach ($boughtProducts as $product) {
                $reportEntry['quantity_purchased'] += $product->quantity;
                $reportEntry['cost'] += $product->quantity * $product->cost;
            }

            // Get destruction data for the current product
            $destructedProducts = Destruction::where('product_id', $productId)
                ->when($startDate)
                ->whereDate('created_at', '>', $startDate)
                ->whereDate('created_at', '<', $toDate);
            if ($employableType) {
                $destructedProducts->where('destructionable_type', $employableType)
                    ->where('destructionable_id', $employableId);
            }
            $destructedQuantity = $destructedProducts->sum('quantity');
            $reportEntry['quantity_disposed'] = $destructedQuantity;

            $expiredQuantity = StoredProduct::query()
                ->where('product_id', $productId)
                ->where('expiration_date', '!=', null)
                ->whereDate('expiration_date', '>', $startDate)
                ->whereDate('expiration_date', '<', $toDate)
                ->whereDate('created_at', '<', $toDate)
                ->when($employableType, function ($query) use ($employableType, $employableId) {
                    $query->where('orderable_by_type', $employableType)
                        ->where('orderable_by_id', $employableId);
                })->sum('expired_quantity');

            $reportEntry['quantity_expired'] = $expiredQuantity;

            $soledProducts = SalesPorduct::query()
                ->where('product_id', $productId)
                ->whereHas('sale', function ($query) use ($employableType, $employableId) {
                    $query->when($employableType, function ($query) use ($employableType, $employableId) {
                        $query->where('orderable_by_type', $employableType)
                            ->where('orderable_by_id', $employableId);
                    });
                })->get();

            foreach ($soledProducts as $product) {
                $reportEntry['quantity_sold'] += $product->quantity;
                $reportEntry['revenue'] += $product->quantity * $product->price;
            }

            $total['quantity_ordered_to_sell'] += $reportEntry['quantity_ordered_to_sell'];
            $total['quantity_sold'] += $reportEntry['quantity_sold'];
            $total['quantity_disposed'] += $reportEntry['quantity_disposed'];
            $total['quantity_expired'] += $reportEntry['quantity_expired'];
            $total['quantity_purchased'] += $reportEntry['quantity_purchased'];
            $total['revenue'] += $reportEntry['revenue'];
            $total['cost'] += $reportEntry['cost'];

            $reportEntry['revenue'] = round($reportEntry['revenue'], 2);
            $reportEntry['cost'] = round($reportEntry['cost'], 2);

            $report['report'][] = $reportEntry;

            // Move to the next period
            $startDate = $this->calculateNextStartDate($startDate, $frequency);
        }

        $total['revenue'] = round($total['revenue'], 2);
        $total['cost'] = round($total['cost'], 2);
        $report['report'][] = $total;

        // Return the report with a success message and status code
        return [
            'data' => $report,
            'message' => __('messages.index_success', ['class' => __('Products')]),
            'code' => 200
        ];
    }

    /**
     * Calculate the end date of the current reporting period based on the frequency.
     *
     * @param Carbon $startDate
     * @param string $frequency
     * @param Carbon $endDate
     * @return Carbon
     */
    private function calculateEndDate(Carbon $startDate, string $frequency, Carbon $endDate): Carbon
    {
        switch ($frequency) {
            case 'daily':
                $toDate = $startDate->copy()->endOfDay();
                break;
            case 'weekly':
                $toDate = $startDate->copy()->endOfWeek();
                break;
            case 'yearly':
                $toDate = $startDate->copy()->endOfYear();
                break;
            case 'monthly':
            default:
                $toDate = $startDate->copy()->endOfMonth();
        }

        return $toDate->gt($endDate) ? $endDate->copy() : $toDate;
    }

    /**
     * Calculate the start date of the next reporting period based on the frequency.
     *
     * @param Carbon $startDate
     * @param string $frequency
     * @return Carbon
     */
    private function calculateNextStartDate(Carbon $startDate, string $frequency): Carbon
    {
        switch ($frequency) {
            case 'daily':
                return $startDate->copy()->addDay()->startOfDay();
            case 'weekly':
                return $startDate->copy()->addWeek()->startOfWeek();
            case 'yearly':
                return $startDate->copy()->addYear()->startOfYear();
            case 'monthly':
            default:
                return $startDate->copy()->addMonth()->startOfMonth();
        }
    }

    /**
     * Generate a report entry for a specific period.
     *
     * @param \Illuminate\Database\Eloquent\Collection $orders
     * @param Carbon $startDate
     * @param Carbon $toDate
     * @return array
     */
    private function generateOrderReportEntry($orders, Carbon $startDate, Carbon $toDate, array &$total): array
    {
        $ordersCount = $orders->count();
        $total['new_orders'] += $ordersCount;

        $reportEntry = [
            'from' => $startDate->toDateString(),
            'to' => $toDate->toDateString(),
            'new_orders' => $ordersCount,
        ];

        foreach ($this->orderStatuses as $statusId => $status) {
            $ordersByStatus = $orders->where('status_id', $statusId);
            $statusCounts = $ordersByStatus->count();

            $reportEntry[strtolower(str_replace(' ', '_', $status)) . '_orders'] = $statusCounts;
            $total[strtolower(str_replace(' ', '_', $status)) . '_orders'] += $statusCounts;

            if ($status == 'Delivered') {
                $ordersCost = $ordersByStatus->sum('order_cost');
                $reportEntry['cost'] = round($ordersCost, 2);
                $total['cost'] += $ordersCost;
            }
        }

        return $reportEntry;
    }
}

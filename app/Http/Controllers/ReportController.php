<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\ReportService;
use App\Http\Responses\Response;
use Illuminate\Http\JsonResponse;
use App\Exports\OrderReportExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\Report\OrderReportRequest;
use App\Http\Requests\Report\ProductReportRequest;

class ReportController extends Controller
{
    private ReportService $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function orderReport(OrderReportRequest $request): JsonResponse
    {
        $data = [];
        try {
            $data = $this->reportService->orderReport($request);
            return Response::Success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    public function productReport(ProductReportRequest $request): JsonResponse
    {
        $data = [];
        try {
            $data = $this->reportService->productReport($request);
            return Response::Success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    public function specificProductReport(Request $request, Product $product):JsonResponse
    {
        $data = [];
        try {
            $data = $this->reportService->specificProductReport($request, $product);
            return Response::Success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    public function orderReportExcel(OrderReportRequest $request)
    {
        $data = $this->reportService->orderReport($request);
        return Excel::download(new OrderReportExport($data['data']), $request->type . '_orders_report.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function orderReportPdf(OrderReportRequest $request)
    {
        $data = $this->reportService->orderReport($request);
        return Excel::download(new OrderReportExport($data['data']), $request->type . '_orders_report.pdf',  \Maatwebsite\Excel\Excel::DOMPDF);
    }
}

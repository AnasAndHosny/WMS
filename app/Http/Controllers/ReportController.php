<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\ReportService;
use App\Http\Responses\Response;
use Illuminate\Http\JsonResponse;
use App\Exports\OrderReportExport;
use App\Exports\ProductReportExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SpecificProductReportExport;
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

    public function specificProductReport(Request $request, Product $product): JsonResponse
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
        return Excel::download(new OrderReportExport($data['data'], 'pdf'), $request->type . '_orders_report.pdf',  \Maatwebsite\Excel\Excel::DOMPDF);
    }

    public function productReportExcel(ProductReportRequest $request)
    {
        $data = $this->reportService->productReport($request);
        return Excel::download(new ProductReportExport($data['data']['report']), 'products_report.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function productReportPdf(ProductReportRequest $request)
    {
        $data = $this->reportService->productReport($request);
        return Excel::download(new ProductReportExport($data['data']['report'], 'pdf'), 'products_report.pdf',  \Maatwebsite\Excel\Excel::DOMPDF);
    }

    public function specificProductReportExcel(Request $request, Product $product)
    {
        $data = $this->reportService->specificProductReport($request, $product);
        $report = $data['data']['report'];
        $productName = strtolower(str_replace(' ', '_', $data['data']['product']['name_en']));

        return Excel::download(new SpecificProductReportExport($report), $productName . '_report.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function specificProductReportPdf(Request $request, Product $product)
    {
        $data = $this->reportService->specificProductReport($request, $product);
        $report = $data['data']['report'];
        $productName = strtolower(str_replace(' ', '_', $data['data']['product']['name_en']));

        return Excel::download(new SpecificProductReportExport($report, 'pdf'), $productName . '_report.pdf',  \Maatwebsite\Excel\Excel::DOMPDF);
    }
}

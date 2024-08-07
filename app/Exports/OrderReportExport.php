<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class OrderReportExport implements FromArray, WithHeadings, WithStrictNullComparison
{
    use Exportable;

    public function __construct(private array $report)
    {
        //
    }

    /**
     * @return array
     */
    public function array(): array
    {
        return $this->report;
    }

    public function headings(): array
    {
        return [
            'from',
            'to',
            'new_orders',
            'pending_orders',
            'deleted_orders',
            'rejected_orders',
            'under_preparing_orders',
            'cancelled_orders',
            'under_shipping_orders',
            'delivered_orders',
            'cost'
        ];
    }
}

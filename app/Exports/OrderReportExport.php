<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class OrderReportExport implements FromArray, WithHeadings, WithStrictNullComparison, WithStyles, WithDefaultStyles
{
    use Exportable;

    public function __construct(private array $report, private string $type = 'excel')
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
            'New Orders',
            'Pending Orders',
            'Deleted Orders',
            'Rejected Orders',
            'Under Preparing Orders',
            'Cancelled Orders',
            'Under Shipping Orders',
            'Delivered Orders',
            'Cost'
        ];
    }

    public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet)
    {

        $styles = $this->type == 'pdf'
            ? [
                1 => [
                    'font' => ['bold' => true, 'size' => 12],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'A9A9A9'],
                    ],
                ],
            ]
            : [];

        return $styles;
    }

    public function defaultStyles(\PhpOffice\PhpSpreadsheet\Style\Style $defaultStyle)
    {
        $defaultStyles = $this->type == 'pdf'
            ? [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
            ]
            : [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ];

        return $defaultStyles;
    }
}

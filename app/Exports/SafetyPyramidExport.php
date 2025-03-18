<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Http\Controllers\DashboardController;

class SafetyPyramidExport implements FromArray, WithHeadings, WithStyles
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
        // return [
        //     ['Fatality', 1],                             // Red
        //     ['Lost Time Injury / Days Away From Work', 3],   // Yellow
        //     ['Restricted Work Case', 5],                // Green
        //     ['Medical Treatment Case', 8],              // Dark Blue (text color will be white)
        //     ['First Aid', 12],                          // Beige
        //     ['Nearmiss', 20],                           // Light Blue
        //     ['Unsafe Act & Condition', 35],             // Olive Green
        // ];
    }

    // Define the heading for the Excel file with two columns
    public function headings(): array
    {
        return ['Category Incident', 'Total'];
    }
   public function styles(Worksheet $sheet)
    {
    // Set column widths for better visibility
    $sheet->getColumnDimension('A')->setWidth(50);
    $sheet->getColumnDimension('B')->setWidth(30);

    // Apply heading style: font size 16px and bold
    $sheet->getStyle('A1:B1')->applyFromArray([
        'font' => [
            'bold' => true,
            'size' => 14,
        ],
        'alignment' => [
            'horizontal' => 'center',
            'vertical' => 'center',
        ],
        'borders' => [
            'outline' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['argb' => '000000'],
            ],
        ],
    ]);

    // Center the values in the 'Total' column
    $sheet->getStyle('B2:B8')->applyFromArray([
        'alignment' => [
            'horizontal' => 'center',
            'vertical' => 'center',
        ],
        'borders' => [
            'outline' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['argb' => '000000'],
            ],
        ],
    ]);

    // Apply borders to the entire pyramid structure for neatness
    for ($i = 2; $i <= 8; $i++) {
        $sheet->getStyle("A$i:B$i")->applyFromArray([
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);
    }

    // Adjust row height for better readability
    foreach (range(1, 8) as $row) {
        if($row == 1) {
            $sheet->getRowDimension($row)->setRowHeight(35);
        } else {
            $sheet->getRowDimension($row)->setRowHeight(22);
        }

    }

    // Apply background colors and other styles (as before)
    // Fatality (Red)
    $sheet->getStyle('A2')->applyFromArray([
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => ['argb' => 'FF0000'],
        ],
        'font' => [
            'color' => ['argb' => 'FFFFFF'], // White text color
        ],
        'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
    ]);

    // Lost Time Injury / Days Away From Work (Yellow)
    $sheet->getStyle('A3')->applyFromArray([
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => ['argb' => 'FFFF00'],
        ],
        'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
    ]);

    // Restricted Work Case (Green)
    $sheet->getStyle('A4')->applyFromArray([
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => ['argb' => '00B050'],
        ],
        'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
    ]);

    // Medical Treatment Case (Dark Blue with White Text)
    $sheet->getStyle('A5')->applyFromArray([
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => ['argb' => '002060'],
        ],
        'font' => [
            'color' => ['argb' => 'FFFFFF'], // White text color
        ],
        'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
    ]);

    // First Aid (Beige)
    $sheet->getStyle('A6')->applyFromArray([
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => ['argb' => 'C6E0B4'],
        ],
        'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
    ]);

    // Nearmiss (Light Blue)
    $sheet->getStyle('A7')->applyFromArray([
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => ['argb' => '4F81BD'],
        ],
        'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
    ]);

    // Unsafe Act & Condition (Olive Green)
    $sheet->getStyle('A8')->applyFromArray([
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => ['argb' => '9BBB59'],
        ],
        'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
    ]);

    // Apply borders to the entire pyramid structure for neatness
    for ($i = 2; $i <= 8; $i++) {
        $sheet->getStyle("B$i")->applyFromArray([
            'font' => [
                'bold' => true,
            ],
        ]);
    }

    return [];
    }
}

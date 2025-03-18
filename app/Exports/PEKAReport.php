<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PEKAReport implements FromArray, WithHeadings, WithStyles
{    
    protected $MAIN;
    protected $CHILD;

    public function __construct(array $data)
    {
        $this->MAIN = $data['MAIN'];
        $this->CHILD = $data['CHILD'];
    }

    public function array(): array
    {
        $mainPekaTable = $this->MAIN;
        $childrenPeka = $this->CHILD;

        // EKSPEKTASI OUTPUT NYA GINI 
        // // Main PEKA data (same as before)
        // $mainPekaTable = [
        //     ['Unsafe Condition', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'],
        //     ['Unsafe Act', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'],
        //     ['Nearmiss', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'],
        //     ['Safety Achievement', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'],
        //     ['Safety Work Hour', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'],
        // ];

        // // Children PEKA data for each category
        // $childrenPeka = [
        //     // Unsafe Condition
        //     ['Unsafe Condition', 'Total'],
        //     ['Tools & Equipment', 651],
        //     ['Line of Fire', 11],
        //     ['Hot Work', '0'],
        //     ['Confined Space', '0'],
        //     ['Powered System', '0'],
        //     ['Lifting Operation', 2],
        //     ['Working At Height', '0'],
        //     ['Ground-Disturbance Work', '0'],
        //     ['Water-Based Work Activities', '0'],
        //     ['Land Transportation', 17],
        //     ['APD', '0'],
        //     ['Housekeeping', 6],
        //     ['Others', 82],

        //     // Unsafe Action
        //     ['Unsafe Action', 'Total'],
        //     ['Tools & Equipment', 66],
        //     ['Line of Fire', 11],
        //     ['Hot Work', '0'],
        //     ['Confined Space', '0'],
        //     ['Powered System', '0'],
        //     ['Lifting Operation', 2],
        //     ['Working At Height', '0'],
        //     ['Ground-Disturbance Work', '0'],
        //     ['Water-Based Work Activities', '0'],
        //     ['Land Transportation', 17],
        //     ['APD', '0'],
        //     ['Housekeeping', 6],
        //     ['Others', 820],

        //     // Add Nearmiss, Safety Achievement, etc., with similar structure
        //     // Nearmiss
        //     ['Nearmiss', 'Total'],
        //     ['Tools & Equipment', 6],
        //     ['Line of Fire', 11],
        //     ['Hot Work', '0'],
        //     ['Confined Space', '0'],
        //     ['Powered System', '0'],
        //     ['Lifting Operation', 2],
        //     ['Working At Height', '0'],
        //     ['Ground-Disturbance Work', '0'],
        //     ['Water-Based Work Activities', '0'],
        //     ['Land Transportation', 17],
        //     ['APD', '0'],
        //     ['Housekeeping', 6],
        //     ['Others', 82],

        //     // Safety Achievement
        //     ['Safety Achievement', 'Total'],
        //     ['Tools & Equipment', 60],
        //     ['Line of Fire', 11],
        //     ['Hot Work', '0'],
        //     ['Confined Space', '0'],
        //     ['Powered System', '0'],
        //     ['Lifting Operation', 2],
        //     ['Working At Height', '0'],
        //     ['Ground-Disturbance Work', '0'],
        //     ['Water-Based Work Activities', '0'],
        //     ['Land Transportation', 17],
        //     ['APD', '0'],
        //     ['Housekeeping', 6],
        //     ['Others', 82],
        // ];


        // dd($this->CHILD);

        // Combine main PEKA table with children PEKA
        return array_merge($mainPekaTable, [['']], $childrenPeka);
    }

    public function headings(): array
    {
        // Only the main PEKA headings
        return [
            ['PEKA', 'Total', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dec'],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Set the height of row 1 (A1:N1)
        foreach(range(1, 6) as $i) {
            $sheet->getRowDimension($i)->setRowHeight(20); // Set the desired height in points
        }

        foreach(range(8, 63) as $i) {
            $sheet->getRowDimension($i)->setRowHeight(15); // Set the desired height in points
        }

        // Style the header row for the main PEKA table
        $sheet->getStyle('A1:N1')->getFont()->setBold(true);
        $sheet->getStyle('A1:A6')->getFont()->getColor()->setRGB('FFFFFF');
        $sheet->getStyle('B1:B6')->getFont()->getColor()->setRGB('004586');
        $sheet->getStyle('C1:N1')->getFont()->getColor()->setRGB('888888');
        $sheet->getStyle('A1:A6')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('004586'); // Dark blue background
        $sheet->getStyle('B1:B6')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('ADD8E6'); // Dark blue background
        $sheet->getStyle('C1:N6')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('F6F6F6'); 
        $sheet->getStyle('A1:N63')->getAlignment()
        ->setHorizontal('center')
        ->setVertical('center'); // Set vertical alignment to middle;
    
        // Merge 'PEKA' cell to span across the entire header
        // foreach(range('A', 'N') as $i) {
        //     $sheet->mergeCells($i.'1:'.$i.'2');
        // }

        // Add borders to the range A1:N6
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'], // Black color
                ],
            ],
        ];
        $sheet->getStyle('A1:N6')->applyFromArray($styleArray);


        $sheet->getStyle('A8:B63')->applyFromArray($styleArray);
        
        // Section title styling (light blue background, bold white text)
        $sectionTitles = [8, 22, 36, 50]; // Rows where section titles start, added Nearmiss and Safety Achievement
        foreach ($sectionTitles as $row) {
            $sheet->getRowDimension($row)->setRowHeight(20); // Set the desired height in points
            $sheet->getStyle("A$row:B$row")->getFont()->setBold(true)->getColor()->setRGB('004586');
            $sheet->getStyle("A$row:B$row")->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('ADD8E6'); // Light blue background for section titles
        }

        // Alternate row colors for data (Zebra striping)
        $dataStartRow = 9; // First row of data
        $dataEndRow = $dataStartRow + 60; // Adjust based on total rows
        // for ($i = $dataStartRow; $i <= $dataEndRow; $i++) {
        //     if ($i % 3 == 0) {
        //         $sheet->getStyle("A$i:B$i")->getFill()
        //             ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        //             ->getStartColor()->setRGB('F2F2F2'); // Light grey background for even rows
        //     }
        // }
    
        // Borders for all data
        // $sheet->getStyle("A1:N$dataEndRow")->getBorders()->getAllBorders()
        //     ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    
        // Center text for certain columns
        // $sheet->getStyle("B2:N$dataEndRow")->getAlignment()->setHorizontal('center');
    
        // Adjust column widths for readability
        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(14);
        for ($col = 'C'; $col <= 'N'; $col++) {
            $sheet->getColumnDimension($col)->setWidth(8);
        }
    
        return [];
    }
    
}

// V1
// namespace App\Exports;

// use Maatwebsite\Excel\Concerns\FromArray;
// use Maatwebsite\Excel\Concerns\WithHeadings;
// use Maatwebsite\Excel\Concerns\WithStyles;
// use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

// class PEKAReport implements FromArray, WithHeadings, WithStyles
// {
//     public function array(): array
//     {
//         // Main PEKA data
//         $mainPekaTable = [
//             ['Unsafe Condition', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
//             ['Unsafe Act', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
//             ['Nearmiss', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
//             ['Safety Achievement', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
//             ['Safety Work Hour', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
//         ];

//         // Combine main PEKA table with children PEKA
//         return array_merge($mainPekaTable, [['']], $this->getChildrenPeka());
//     }

//     private function getChildrenPeka(): array
//     {
//         return [
//             // Unsafe Condition
//             [
//                 ['Unsafe Condition', 'Total'],
//                 ['Tools & Equipment', 60],
//                 ['Line of Fire', 11],
//                 ['Hot Work', 0],
//                 ['Confined Space', 0],
//                 ['Powered System', 0],
//                 ['Lifting Operation', 2],
//                 ['Working At Height', 0],
//                 ['Ground-Disturbance Work', 0],
//                 ['Water-Based Work Activities', 0],
//                 ['Land Transportation', 17],
//                 ['APD', 0],
//                 ['Housekeeping', 6],
//                 ['Others', 820],
//             ],

//             // Unsafe Action
//             [
//                 ['Unsafe Action', 'Total'],
//                 ['Tools & Equipment', 60],
//                 ['Line of Fire', 11],
//                 ['Hot Work', 0],
//                 ['Confined Space', 0],
//                 ['Powered System', 0],
//                 ['Lifting Operation', 2],
//                 ['Working At Height', 0],
//                 ['Ground-Disturbance Work', 0],
//                 ['Water-Based Work Activities', 0],
//                 ['Land Transportation', 17],
//                 ['APD', 0],
//                 ['Housekeeping', 6],
//                 ['Others', 820],
//             ],

//             // Nearmiss
//             [
//                 ['Nearmiss', 'Total'],
//                 ['Tools & Equipment', 60],
//                 ['Line of Fire', 11],
//                 ['Hot Work', 0],
//                 ['Confined Space', 0],
//                 ['Powered System', 0],
//                 ['Lifting Operation', 2],
//                 ['Working At Height', 0],
//                 ['Ground-Disturbance Work', 0],
//                 ['Water-Based Work Activities', 0],
//                 ['Land Transportation', 17],
//                 ['APD', 0],
//                 ['Housekeeping', 6],
//                 ['Others', 820],
//             ],

//             // Safety Achievement
//             [
//                 ['Safety Achievement', 'Total'],
//                 ['Tools & Equipment', 60],
//                 ['Line of Fire', 11],
//                 ['Hot Work', 0],
//                 ['Confined Space', 0],
//                 ['Powered System', 0],
//                 ['Lifting Operation', 2],
//                 ['Working At Height', 0],
//                 ['Ground-Disturbance Work', 0],
//                 ['Water-Based Work Activities', 0],
//                 ['Land Transportation', 17],
//                 ['APD', 0],
//                 ['Housekeeping', 6],
//                 ['Others', 820],
//             ]
//         ];
//     }

//     public function headings(): array
//     {
//         return [
//             ['PEKA', 'Total', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dec'],
//         ];
//     }

//     public function styles(Worksheet $sheet)
//     {
//         // Header styles
//         $sheet->getStyle('A1:N1')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
//         $sheet->getStyle('A1:N1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
//             ->getStartColor()->setRGB('004586');
//         $sheet->getStyle('A1:N1')->getAlignment()->setHorizontal('center');

//         // Merge header cell for PEKA table
//         $sheet->mergeCells('A1:N1');

//         // Section titles styles (example)
//         $sectionTitles = [8, 23, 36, 50]; // Adjust as needed
//         foreach ($sectionTitles as $row) {
//             $sheet->getStyle("A$row:B$row")->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
//             $sheet->getStyle("A$row:B$row")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
//                 ->getStartColor()->setRGB('ADD8E6');
//         }

//         // Set up table layout
//         $currentRow = 2; // Start below the header
//         foreach ($this->getChildrenPeka() as $children) {
//             // Calculate the number of rows needed
//             $numRows = count($children);
//             // Example: Set data starting from currentRow
//             $sheet->fromArray($children, null, "A$currentRow");
//             // Increment the currentRow by the number of rows in the children
//             $currentRow += $numRows + 2; // Add extra space between tables
//         }

//         // Adjust column widths for readability
//         $sheet->getColumnDimension('A')->setWidth(30);
//         $sheet->getColumnDimension('B')->setWidth(15);
//         for ($col = 'C'; $col <= 'N'; $col++) {
//             $sheet->getColumnDimension($col)->setWidth(12);
//         }

//         return [];
//     }
// }


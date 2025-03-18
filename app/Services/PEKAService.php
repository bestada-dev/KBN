<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\ReportPekaModel;
use App\ReportDailyModel;
use Carbon\Carbon;
use Auth;

class PEKAService
{

    public function getData($request, $type = 'for JSON')
    {
        // Get the requested year or default to the current year
        $year = $request->input('year', now()->year);

        // Initialize the main PEKA table with monthly data for each kind of report
        $mainPekaTable = [
            ['Unsafe Condition', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'],
            ['Unsafe Action', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'],
            ['Nearmiss', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'],
            ['Safety Achievement', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'],
            ['Safety Work Hour', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'],
        ];

        // Array of months for reference (1-based indexing for easier handling)
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        // // Get all reports for the specific year
        // $reports = ReportPekaModel::whereYear('created_at', $year)->get();

        // // Loop through each entry in $mainPekaTable to calculate counts for each month
        // foreach ($mainPekaTable as $index => $kindOfReportRow) {
        //     $kindOfReport = $kindOfReportRow[0];  // Get the kind of report (e.g., 'Unsafe Condition')

        //     // Fetch reports for the current kind of report
        //     $filteredReports = $reports->where('kind_of_report', $kindOfReport);

        //     // Calculate the total count for the kind of report
        //     $totalCount = $filteredReports->count();
        //     $mainPekaTable[$index][1] = $totalCount; // Update the 'Total' column

        //     // Loop through each month and count the reports
        //     foreach ($months as $monthIndex => $monthName) {
        //         $monthlyCount = $filteredReports->filter(function ($report) use ($monthIndex) {
        //             // Check if the report's created_at month matches the current month
        //             return Carbon::parse($report->created_at)->month === ($monthIndex + 1);
        //         })->count();

        //         // Update the corresponding month's count in the table (shift by 2 to account for 'Kind Of Work' and 'Total' columns)
        //         $mainPekaTable[$index][$monthIndex + 2] = $monthlyCount;
        //     }
        // }

        // Get all PEKA reports for the specific year
        $user = Auth::user();
        $projectIds = $user->projects()->pluck('projects.id')->toArray();
        $reports = ReportPekaModel::whereYear('created_at', $year);
        
        if (!empty($request['project_id'])) {
            $reports->where('project_id', $request['project_id']);
        } else {
            if (Auth::check()) {
                if(!$user->is_admin) {
                    $reports->whereIn('project_id', $projectIds);
                }
            }
        }

        if (!empty($request['month'])) {
            $reports->whereMonth('created_at', $request['month']);
        }

        if (!empty($request['year'])) {
            $reports->whereYear('created_at', $request['year']);
        }
        
        $reports = $reports->get();

        // Process reports for Unsafe Conditions, Unsafe Actions, Nearmiss, Safety Achievement (Existing logic)
        foreach ($mainPekaTable as $index => $kindOfReportRow) {
            $kindOfReport = $kindOfReportRow[0];  // Get the kind of report (e.g., 'Unsafe Condition')

            // Fetch reports for the current kind of report
            $filteredReports = $reports->where('kind_of_report', $kindOfReport);

            // Calculate the total count for the kind of report
            $totalCount = $filteredReports->count();
            $mainPekaTable[$index][1] = $totalCount === 0 ? '0' : $totalCount; // Update the 'Total' column

            // Loop through each month and count the reports
            foreach ($months as $monthIndex => $monthName) {
                $monthlyCount = $filteredReports->filter(function ($report) use ($monthIndex) {
                    return Carbon::parse($report->created_at)->month === ($monthIndex + 1);
                })->count();

                // Update the corresponding month's count in the table (shift by 2 to account for 'Kind Of Work' and 'Total' columns)
                $mainPekaTable[$index][$monthIndex + 2] = $monthlyCount === 0 ? '0' : $monthlyCount;
            }
        }

        // New logic for Safety Work Hour (index 4 in $mainPekaTable)

        // Fetch daily reports for the year
        // dd($projectIds);
        $dailyReports = ReportDailyModel::whereYear('created_at', $year);
           
        if (!empty($request['project_id'])) {
            $dailyReports->where('project_id', $request['project_id']);
        } else {
            if (Auth::check()) {
                if(!$user->is_admin) {
                    $dailyReports = $dailyReports->whereIn('project_id', $projectIds);
                }
            }
        }

        if (!empty($request['month'])) {
            $dailyReports->whereMonth('created_at', $request['month']);
        }

        if (!empty($request['year'])) {
            $dailyReports->whereYear('created_at', $request['year']);
        }
 
        $dailyReports = $dailyReports->get();

        // Accumulate safety work hours per month
        $grandTotalSafetyWorkHours = '0';
        foreach ($dailyReports as $dailyReport) {
            $workingDate = Carbon::parse($dailyReport->created_at);
            $monthIndex = $workingDate->month - 1; // Get zero-based index for the month

            // Calculate the number of hours worked in the day
            $clockIn = Carbon::parse($dailyReport->c_in);
            $clockOut = Carbon::parse($dailyReport->c_out);
            $hoursWorked = $clockOut->diffInHours($clockIn);

            // Calculate the total safety work hours for the day
            $safetyWorkHours = $hoursWorked * $dailyReport->number_of_worker;
            
            // Add the calculated safety work hours to the corresponding month in the 'Safety Work Hour' row
            $mainPekaTable[4][$monthIndex + 2] += $safetyWorkHours;
            $grandTotalSafetyWorkHours += $safetyWorkHours;
        }

        $mainPekaTable[4][1] = number_format($grandTotalSafetyWorkHours); // Update the 'Total' column

        // CHILDREN PEKA

        // Define the categories under each main type of report
        $categories = [
            'Unsafe Condition' => [
                'Tools & Equipment', 'Line of Fire', 'Hot Work', 'Confined Space', 'Powered System',
                'Lifting Operation', 'Working At Height', 'Ground-Disturbance Work', 'Water-Based Work Activities',
                'Land Transportation', 'Personal Protective Equipment', 'Housekeeping', 'Others'
            ],
            'Unsafe Action' => [
                'Tools & Equipment', 'Line of Fire', 'Hot Work', 'Confined Space', 'Powered System',
                'Lifting Operation', 'Working At Height', 'Ground-Disturbance Work', 'Water-Based Work Activities',
                'Land Transportation', 'Personal Protective Equipment', 'Housekeeping', 'Others'
            ],
            'Nearmiss' => [
                'Tools & Equipment', 'Line of Fire', 'Hot Work', 'Confined Space', 'Powered System',
                'Lifting Operation', 'Working At Height', 'Ground-Disturbance Work', 'Water-Based Work Activities',
                'Land Transportation', 'Personal Protective Equipment', 'Housekeeping', 'Others'
            ],
            'Safety Achievement' => [
                'Tools & Equipment', 'Line of Fire', 'Hot Work', 'Confined Space', 'Powered System',
                'Lifting Operation', 'Working At Height', 'Ground-Disturbance Work', 'Water-Based Work Activities',
                'Land Transportation', 'Personal Protective Equipment', 'Housekeeping', 'Others'
            ],
        ];

        // Initialize the children PEKA array
        $childrenPeka = [];

        // Process the data into main PEKA and child PEKA tables
        foreach ($categories as $kindOfReport => $categoryList) {
            $total = 0;
            $childrenPeka[] = [$kindOfReport, 'Total'];

            foreach ($categoryList as $category) {
                // Count the number of reports for each category under this kind_of_report
                $count = $reports->where('kind_of_report', $kindOfReport)
                                ->where('clsr_category', $category)
                                ->count();

                $childrenPeka[] = [$category, $count];
                $total += $count;
            }
        }

        foreach($childrenPeka as &$item) {
            if ($item[1] === 0) {
                $item[1] = '0';
            }
        }
        // Return the mainPekaTable data as needed (JSON for example)
        if($type === 'for EXCEL') {
            return [ 
                'MAIN' => $mainPekaTable, 
                'CHILD' => $childrenPeka 
            ];
        } else if($type === 'for JSON') {
            return response()->json([
                'status' => true,
                'data' =>  ['MAIN'=>$mainPekaTable, 'CHILD' => $childrenPeka]
            ]);
        } else if($type === 'for BLADE') {

            $result = [];
            $currentCategory = '';

            foreach ($childrenPeka as $item) {
                if ($item[1] === "Total") {
                    // If the item is a category, set the current category
                    $currentCategory = $item[0];
                    $result[$currentCategory] = []; // Initialize an empty array for the category
                } else {
                    // Otherwise, add the item to the current category
                    $result[$currentCategory][] = [
                        "name" => $item[0],
                        "value" => $item[1]
                    ];
                }
            }
            return ['MAIN'=>$mainPekaTable, 'CHILD' => $result];
        }
    }
}

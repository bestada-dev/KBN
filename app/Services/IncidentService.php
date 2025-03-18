<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\ReportPekaModel;
use Auth;

class IncidentService
{
    protected $categoryMapping = [
        'Death (Fatality)' => 'Fatality',
        'Days Away From Work (DAFW)' => 'Lost Time Injury / Days Away From Work',
        'Restricted Work Case (RWC)' => 'Restricted Work Case',
        'Medical Treatment Case (MTC)' => 'Medical Treatment Case',
        'First Aid' => 'First Aid',
        'Nearmiss' => 'Nearmiss',
        // 'HIPO' => 'Nearmiss',
        // 'Kebakaran/Ledakan' => 'Unsafe Act & Condition',
        // 'Kebocoran' => 'Unsafe Act & Condition',
        // 'Kecelakaan Sarfas' => 'Unsafe Act & Condition',
        // 'Kegagalan Aset/Sarfas' => 'Unsafe Act & Condition',
    ];

    public function getData($request)
    {

        $user = Auth::user();
        $projectIds = $user->projects()->pluck('projects.id')->toArray();

        $query = DB::table('incident_reports')
            ->select('incident_category_id', DB::raw('count(*) as count'))
            ->groupBy('incident_category_id');

        // Apply filters if provided
        if (!empty($request['project_id'])) {
            $query->where('project_id', $request['project_id']);
        } else {
            if (Auth::check()) {
                if(!$user->is_admin) {
                    $query->whereIn('project_id', $projectIds);
                }
            }
        }

        if (!empty($request['month'])) {
            $query->whereMonth('created_at', $request['month']);
        }

        if (!empty($request['year'])) {
            $query->whereYear('created_at', $request['year']);
        }


        // Get counts
        $counts = $query->get()
            ->keyBy('incident_category_id')
            ->map->count;

        // Fetch category names
        $categories = DB::table('category_incident')
            ->pluck('name', 'id');

        // Determine the range of categories to include
        $categoryKeys = array_keys($this->categoryMapping);
        $startCategory = 'Death (Fatality)';
        $endCategory = 'Nearmiss';

        $includeCategories = array_slice(
            $categoryKeys,
            array_search($startCategory, $categoryKeys),
            array_search($endCategory, $categoryKeys) - array_search($startCategory, $categoryKeys) + 1
        );

        // Compute category counts
        $categoryCounts = [];
        
        foreach ($categories as $id => $name) {
            if (in_array($name, $includeCategories)) {
                $displayName = $this->categoryMapping[$name] ?? $name;

                if (!isset($categoryCounts[$displayName])) {
                    $categoryCounts[$displayName] = 0;
                }

                $categoryCounts[$displayName] += $counts->get($id, 0);
            }
        }


        $whatKindOfReport = ['Unsafe Condition', 'Unsafe Action'];

        $query = ReportPekaModel::whereIn('kind_of_report', $whatKindOfReport);

        if (!empty($request['project_id'])) {
            $query->where('project_id', $request['project_id']);
        } else {
            if (Auth::check()) {
                if(!$user->is_admin) {
                    $query->whereIn('project_id', $projectIds);
                }
            };
        }

        if (!empty($request['month'])) {
            $query->whereMonth('created_at', $request['month']);
        }

        if (!empty($request['year'])) {
            $query->whereYear('created_at', $request['year']);
        }
       
        // Ensure the new category is always at the bottom
        $categoryCounts['Unsafe Act & Condition'] = $query->count();

        return array_map(function ($name, $count) {
            return [$name, $count];
        }, array_keys($categoryCounts), $categoryCounts);
    }
}

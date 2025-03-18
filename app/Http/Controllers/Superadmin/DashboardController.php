<?php

namespace App\Http\Controllers\Superadmin;

use App\CategoryModel;
use App\Visitor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $req)
    {
        $monthly_visitor_month_filter = $req->monthly_visitor_month_filter ?? now()->month;
        $monthly_visitor_year_filter = $req->monthly_visitor_year_filter ?? now()->year;
        
        $startOfMonth = Carbon::create($monthly_visitor_year_filter, $monthly_visitor_month_filter, 1)->startOfMonth();
        $endOfMonth = Carbon::create($monthly_visitor_year_filter, $monthly_visitor_month_filter, 1)->endOfMonth();
    
        $visitors = [
            'dashboard' => [
                'month' => [
                    'this' => $this->getMonthlyVisitorCount(now()->month, now()->year),
                    'last' => $this->getMonthlyVisitorCount(now()->subMonth()->month, now()->year),
                ],
                'year' => [
                    'this' => $this->getYearlyVisitorCount(now()->year),
                    'last' => $this->getYearlyVisitorCount(now()->subYear()->year),
                ],
            ],
            'countries' => $this->visitorCountries($req),
        ];
    
        $master_categories = CategoryModel::withCount('getProperty')->get();
    
        return view('pages.admin.dashboard.index', compact('master_categories', 'visitors'));
    }    
    
    protected function getMonthlyVisitorCount($month, $year)
    {
        return Visitor::whereMonth('created_at', $month)
                      ->whereYear('created_at', $year)
                      ->count();
    }
    
    protected function getYearlyVisitorCount($year)
    {
        return Visitor::whereYear('created_at', $year)->count();
    }
    
    protected function getMonthlyVisitorsData($year, $startOfMonth, $endOfMonth)
    {
        $dailyVisitors = [];
    
        $currentDay = $startOfMonth->copy();
        
        while ($currentDay <= $endOfMonth) {
            $dailyVisitors[$currentDay->day] = Visitor::whereDate('created_at', $currentDay->format('Y-m-d'))->count();
    
            $currentDay->addDay();
        }
    
        return $dailyVisitors;
    }    

    protected function visitorCountries(Request $req)
    {
        $query = Visitor::query()
            ->selectRaw('
                country_name,
                country_code,
                COUNT(*) AS total_visitors
            ')
            ->with(['user:id,name'])
            ->groupBy('country_name')
            ->get()
            ->map(function ($item) {
                $visitorUsers = Visitor::where('country_name', $item->country_name)
                    ->with('user:id,name')
                    ->limit(5)
                    ->get()
                    ->pluck('user');
    
                return [
                    'country_name' => $item->country_name,
                    'country_code' => strtolower($item->country_code,),
                    'visitor_users' => $visitorUsers,
                    'total_visitors' => $item->total_visitors,
                ];
            });

        return $query;
    }        

    // API
        public function getMonthlyVisitorsDataAPI(Request $req) 
        {
            $monthly_visitor_month_filter = $req->monthly_visitor_month_filter ?? now()->month;
            $monthly_visitor_year_filter = $req->monthly_visitor_year_filter ?? now()->year;
            
            $startOfMonth = Carbon::create($monthly_visitor_year_filter, $monthly_visitor_month_filter, 1)->startOfMonth();
            $endOfMonth = Carbon::create($monthly_visitor_year_filter, $monthly_visitor_month_filter, 1)->endOfMonth();  
          
            return $this->getMonthlyVisitorsData($monthly_visitor_year_filter, $startOfMonth, $endOfMonth);
        }

        public function getMonthlyVisitorsByCountryDataAPI(Request $req) 
        {
            $monthly_visitor_month_filter = $req->monthly_visitor_month_filter ?? now()->month;
            $monthly_visitor_year_filter = $req->monthly_visitor_year_filter ?? now()->year;

            $visitors = Visitor::selectRaw('country_code, COUNT(*) as total_visitors')
                ->whereMonth('created_at', $monthly_visitor_month_filter)
                ->whereYear('created_at', $monthly_visitor_year_filter)
                ->groupBy('country_code')
                ->get();

            return response()->json([
                'status' => true,
                'data' => $visitors,
                'message' => 'Monthly visitors grouped by country code retrieved successfully',
            ]);
        }

        public function masterCategoryPropertiesDataAPI()
        {
            $categories = CategoryModel::select('id', 'name')->get();
        
            return response()->json([
                'status' => true,
                'data' => $categories,
                'message' => 'Master property categories retrieved successfully',
            ]);
        }        
      
        public function tenMostViewedPropertiesDataAPI(Request $req)
        {
            $category_id = $req->category_id;
            $month = $req->monthly_visitor_month_filter ?? now()->month;
            $year = $req->monthly_visitor_year_filter ?? now()->year;
        
            $query = Visitor::query()
                ->selectRaw('
                    property.id AS property_id, 
                    property.property_address, 
                    COUNT(visitors.id) AS total_visitors
                ')
                ->join('property', 'property.id', '=', 'visitors.property_id')
                ->whereMonth('visitors.created_at', $month)
                ->whereYear('visitors.created_at', $year)
                ->when($category_id, function ($query) use ($category_id) {
                    $query->where('property.category_id', $category_id);
                })
                ->groupBy('property.id')
                ->orderByDesc('total_visitors')
                ->limit(10)
                ->get();
        
            return response()->json([
                'status' => true,
                'message' => 'Successfully retrieved top 10 most viewed properties.',
                'data' => $query,
            ]);
        }
    // API
}

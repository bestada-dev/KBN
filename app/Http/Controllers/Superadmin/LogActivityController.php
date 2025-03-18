<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use App\LogUsersModel;

class LogActivityController extends Controller
{
    function index(){
        return view('pages.admin.log_activity.index');
    }

    // public function dataTable(Request $request)
    // {
    //     try {

    //         $columns = [null, 'name'];
    //         $limit = $request->input('length');
    //         $start = $request->input('start');
    //         $order = $columns[$request->input('order.0.column')] ?? 'id';
    //         $dir = $request->input('order.0.dir') ?? 'DESC';

    //         $data = [];
    //         $totalData = LogUsersModel::count();
    //         $totalFiltered = $totalData;
    //         $posts = '';

    //         if (empty($request->input('search.value'))) {
    //             $query = LogUsersModel::query();

    //             $posts = $query->offset($start)
    //                 ->limit($limit)
    //                 ->orderBy($order, $dir)
    //                 ->get();

    //         } else {
    //             $search = $request->input('search.value');

    //             // Grouping the search conditions to enforce `role_id = 1` for all search results
    //             $tb = LogUsersModel::where(function($query) use ($search) {
    //                     $query->where('id', 'LIKE', "%{$search}%")
    //                         ->orWhere('user_name', 'LIKE', "%{$search}%")
    //                         ->orWhere('type', 'LIKE', "%{$search}%")
    //                         ->orWhere('activity', 'LIKE', "%{$search}%");
    //                 });


    //             $posts = $tb->offset($start)
    //                 ->limit($limit)
    //                 ->orderBy($order, $dir)
    //                 ->get();

    //             $totalFiltered = $tb->count();
    //         }

    //         // dd($posts);

    //         if (!empty($posts)) {

    //             $no = $start + 1;

    //             foreach ($posts as $a) {
    //                 // dd($a);
    //                 $d = $a;

    //                 $data[] = $d;
    //             }
    //         }
    //         // dd('asdasd = ', $data);
    //         $json_data = [
    //             "draw" => intval($request->input('draw')),
    //             "recordsTotal" => intval($totalData),
    //             "recordsFiltered" => intval($totalFiltered),
    //             "data" => $data
    //         ];

    //         return response()->json($json_data);

    //     } catch (Exception $e) {

    //         return ____error([__FUNCTION__, 'Line : '.$e->getLine(). ' Message : '.$e->getMessage()]);

    //     }
    // }

    public function dataTable(Request $request)
    {
        try {
            $columns = [null, 'user_name', 'type'];
            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')] ?? 'id';
            $dir = $request->input('order.0.dir') ?? 'DESC';

            $tanggalFilter = $request->input('tanggal'); // Get the type filter value

            if (!empty($tanggalFilter)) {
                // Parsing tanggal dengan format d/m/Y dan mengonversinya ke format Y-d-m
                $tanggalFilter = Carbon::createFromFormat('d/m/Y', $tanggalFilter)->format('Y-m-d');
            }

            $data = [];
            $totalData = LogUsersModel::count();
            $totalFiltered = $totalData;

            // Start building the query
            $query = LogUsersModel::query();

            // Handle search functionality
            $search = $request->input('search.value');
            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('id', 'LIKE', "%{$search}%")
                    ->orWhere('user_name', 'LIKE', "%{$search}%")
                    ->orWhere('type', 'LIKE', "%{$search}%")
                    ->orWhere('activity', 'LIKE', "%{$search}%");
                });
            }

            // Filter by status if provided
            if (!empty($tanggalFilter)) {
                // $formattedTanggal = Carbon::createFromFormat('d-m-Y', $tanggal)->format('Y-m-d');
                $query->where('date', $tanggalFilter);
            }

            // Get the filtered results with pagination
            $posts = $query->offset($start)
                        ->limit($limit)
                        ->orderBy($order, $dir)
                        ->get();

            // Update the total filtered count
            $totalFiltered = $query->count();

            // Populate the data array
            foreach ($posts as $a) {
                $data[] = $a;
            }

            // Prepare and return the JSON response
            $json_data = [
                "draw" => intval($request->input('draw')),
                "recordsTotal" => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data" => $data
            ];

            return response()->json($json_data);

        } catch (Exception $e) {
            return response()->json([
                'error' => [
                    'function' => __FUNCTION__,
                    'line' => $e->getLine(),
                    'message' => $e->getMessage()
                ]
            ]);
        }
    }
}

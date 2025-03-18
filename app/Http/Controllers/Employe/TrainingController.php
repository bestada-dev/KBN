<?php

namespace App\Http\Controllers\Employe;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Auth;

use App\AksesPelatihanSettingModel;
use App\EmployeTestModel;

class TrainingController extends Controller
{
    function index(){
        return view('pages.employe.pelatihanSaya.index');
    }

    function detail($id){
        // return EmployeTestModel::with('getPelatihan')->where('id', $id)->first();
        return view('pages.employe.pelatihanSaya.detail', [
            'edit_data' => EmployeTestModel::with('getPelatihan')->where('id', $id)->first()
        ]);
    }

    // public function dataTable(Request $request)
    // {
    //     try {
    //         $columns = [null, 'code'];
    //         $limit = $request->input('length');
    //         $start = $request->input('start');
    //         $order = $columns[$request->input('order.0.column')] ?? 'id';
    //         $dir = $request->input('order.0.dir') ?? 'DESC';

    //         $data = [];

    //         // Total data count for pagination
    //         $totalData = AksesPelatihanSettingModel::with(['getPelatihanDanPengembangan', 'company'])
    //                         ->where('company_id', Auth::user()->company_id)
    //                         ->count();
    //         $totalFiltered = $totalData;
    //         $posts = '';

    //         // Handle search functionality
    //         $search = $request->input('search.value');
    //         $type_pelatihan = $request->input('type_pelatihan'); // Get the type filter value

    //         $query = AksesPelatihanSettingModel::with(['getPelatihanDanPengembangan', 'company'])
    //                         ->where('company_id', Auth::user()->company_id);

    //         if (!empty($search)) {
    //             $query->where(function ($q) use ($search, $type_pelatihan) {
    //                 $q->where('id', 'LIKE', "%{$search}%")
    //                   ->orWhere('code', 'LIKE', "%{$search}%")
    //                   ->orWhereHas('getPelatihanDanPengembangan', function ($q) use ($search) {
    //                       $q->where('judul_pelatihan', 'LIKE', "%{$search}%");
    //                   });

    //                 // Include the type filter if it's set
    //                 if (!empty($type_pelatihan)) {
    //                     $q->orWhereHas('getPelatihanDanPengembangan', function ($q) use ($type_pelatihan) {
    //                         $q->where('type', 'LIKE', "%{$type_pelatihan}%");
    //                     });
    //                 }
    //             });
    //         }

    //         // Get filtered results with pagination
    //         $posts = $query->offset($start)
    //                        ->limit($limit)
    //                        ->orderBy($order, $dir)
    //                        ->get();

    //         $totalFiltered = $query->count(); // Update the total filtered count

    //         // Populate data array
    //         foreach ($posts as $a) {
    //             $data[] = $a;
    //         }

    //         // Prepare JSON response
    //         $json_data = [
    //             "draw" => intval($request->input('draw')),
    //             "recordsTotal" => intval($totalData),
    //             "recordsFiltered" => intval($totalFiltered),
    //             "data" => $data
    //         ];

    //         return response()->json($json_data);

    //     } catch (Exception $e) {
    //         return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
    //     }
    // }

    public function dataTable(Request $request)
    {
        try {
            $columns = [null];
            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')] ?? 'id';
            $dir = $request->input('order.0.dir') ?? 'DESC';

            $data = [];

            // Total data count for pagination
            $totalData = EmployeTestModel::with('getPelatihan.getTestByPelatihanAndPengembangan')
                            ->where('user_id', Auth::user()->id)
                            ->count();
            $totalFiltered = $totalData;
            $posts = '';

            // Handle search functionality
            $search = $request->input('search.value');
            $kategori_pelatihan = $request->input('kategori_pelatihan'); // Get the type filter value

            $query = EmployeTestModel::with('getPelatihan.getTestByPelatihanAndPengembangan')
                            ->where('user_id', Auth::user()->id);

            // Apply the search filter if provided
            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('id', 'LIKE', "%{$search}%")
                    ->orWhereHas('getPelatihan', function ($q) use ($search) {
                        $q->where(function ($subQuery) use ($search) {
                            $subQuery->where('id_pelatihan', 'LIKE', "%{$search}%")
                                    ->orWhere('judul_pelatihan', 'LIKE', "%{$search}%");
                        });
                    });
                });
            }

            // Apply the type filter if provided
            if (!empty($kategori_pelatihan)) {
                $query->whereHas('getPelatihan', function ($q) use ($kategori_pelatihan) {
                    $q->where('type', 'LIKE', "%{$kategori_pelatihan}%");
                });
            }


            // Get filtered results with pagination
            $posts = $query->offset($start)
                           ->limit($limit)
                           ->orderBy($order, $dir)
                           ->get();

            $totalFiltered = $query->count(); // Update the total filtered count

            // Populate data array
            foreach ($posts as $a) {
                $data[] = $a;
            }

            // Prepare JSON response
            $json_data = [
                "draw" => intval($request->input('draw')),
                "recordsTotal" => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data" => $data
            ];

            return response()->json($json_data);

        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
}

<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Auth;

use App\AksesPelatihanSettingModel;

class PengembanganController extends Controller
{
    function index(){
        return view('pages.company.pengembanganSaya.index');
    }

    function detail($id){
        // return AksesPelatihanSettingModel::with('getPelatihanDanPengembangan')->where('id', $id)->first();
        return view('pages.company.pengembanganSaya.detail', [
            'edit_data' => AksesPelatihanSettingModel::where('id', $id)->first()
        ]);
    }

    public function dataTable(Request $request)
    {
        try {
            $columns = [null, 'code'];
            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')] ?? 'id';
            $dir = $request->input('order.0.dir') ?? 'DESC';

            $data = [];

            // Total data count for pagination
            $totalData = AksesPelatihanSettingModel::with(['getPelatihanDanPengembangan', 'company'])
                            ->where('type', 'Pengembangan')
                            ->where('company_id', Auth::user()->company_id)
                            ->count();
            $totalFiltered = $totalData;
            $posts = '';

            // Handle search functionality
            $search = $request->input('search.value');
            $type_pelatihan = $request->input('type_pelatihan'); // Get the type filter value

            $query = AksesPelatihanSettingModel::with(['getPelatihanDanPengembangan', 'company'])
                            ->where('type', 'Pengembangan')
                            ->where('company_id', Auth::user()->company_id);

            if (!empty($search)) {
                $query->where(function ($q) use ($search, $type_pelatihan) {
                    $q->where('id', 'LIKE', "%{$search}%")
                      ->orWhere('code', 'LIKE', "%{$search}%")
                      ->orWhereHas('getPelatihanDanPengembangan', function ($q) use ($search) {
                          $q->where('judul_pelatihan', 'LIKE', "%{$search}%");
                      });

                    // Include the type filter if it's set
                    if (!empty($type_pelatihan)) {
                        $q->orWhereHas('getPelatihanDanPengembangan', function ($q) use ($type_pelatihan) {
                            $q->where('type', 'LIKE', "%{$type_pelatihan}%");
                        });
                    }
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

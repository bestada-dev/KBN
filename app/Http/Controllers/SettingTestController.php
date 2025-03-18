<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Auth;
use DB;
use Exception; // Import the Exception class
use Log;

use App\PelatihanSayaModel;
use App\TestModel;

class SettingTestController extends Controller
{
    function index(){
        // return PelatihanSayaModel::with('getTestByPelatihanAndPengembangan')->get();
        return view('pages.admin.setting_test.index');
    }

    function detailPelatihan($id){
        $data = PelatihanSayaModel::with('getTestByPelatihanAndPengembangan')->where('id', $id)->first();
        return response()->json($data);
    }

    public function updateTestStatus(Request $request)
    {

        $tests = collect($request->input('tests'))->map(function ($test) {
            $test['is_locked'] = $test['is_locked'] == 2 ? 0 : 1;
            return $test;
        })->toArray();


        $validator = Validator::make(['tests' => $tests], [
            'tests' => 'required|array',
            'tests.*.id' => 'required|integer|exists:tests,id',
            'tests.*.is_locked' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed.', 'errors' => $validator->errors()], 422);
        }

        try {

            foreach ($tests as $testData) {
                TestModel::where('id', $testData['id'])
                    ->update(['is_locked' => $testData['is_locked']]);
            }

            return response()->json(['message' => 'Test statuses updated successfully.'], 200);
        } catch (\Exception $e) {

            Log::error("Error updating test status: " . $e->getMessage());

            return response()->json(['message' => 'Failed to update test statuses.'], 500);
        }
    }





    // public function dataTable(Request $request)
    // {
    //     try {
    //         $columns = [null, 'code', 'judul_pelatihan_id'];
    //         $limit = $request->input('length');
    //         $start = $request->input('start');
    //         $order = $columns[$request->input('order.0.column')] ?? 'id';
    //         $dir = $request->input('order.0.dir') ?? 'DESC';

    //         $data = [];
    //         $totalData = PelatihanSayaModel::count();
    //         $totalFiltered = $totalData;

    //         // Ambil nilai filter dari request
    //         $search = $request->input('search.value');
    //         $tanggal = $request->input('tanggal');

    //         // Filter query utama
    //         $query = PelatihanSayaModel::when($search, function ($q) use ($search) {
    //                 $q->where('id', 'LIKE', "%{$search}%")
    //                 ->orWhere('code', 'LIKE', "%{$search}%")
    //                 ->orWhere('tanggal_mulai', 'LIKE', "%{$tanggal}%");
    //             });

    //         $totalFiltered = $query->count();

    //         // Ambil data dengan batas dan urutan
    //         $posts = $query->offset($start)
    //             ->limit($limit)
    //             ->orderBy($order, $dir)
    //             ->get();

    //         // Format hasil data
    //         if (!empty($posts)) {
    //             $no = $start + 1;
    //             foreach ($posts as $a) {
    //                 $d = $a;
    //                 $data[] = $d;
    //                 $d['pelatihan'] = $a->aksesPelatihan ? $a->aksesPelatihan : '-';
    //                 $d['test'] = $a->getTestByPelatihanAndPengembangan ? $a->getTestByPelatihanAndPengembangan : '-';
    //             }
    //         }

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
            $columns = [null, 'code', 'judul_pelatihan_id'];
            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')] ?? 'id';
            $dir = $request->input('order.0.dir') ?? 'DESC';

            $data = [];
            $totalData = PelatihanSayaModel::count();
            $totalFiltered = $totalData;

            // Ambil nilai filter dari request
            $search = $request->input('search.value');
            $tanggal = $request->input('tanggal');

            // Filter query utama
            $query = PelatihanSayaModel::when($search, function ($q) use ($search, $tanggal) {
                    $q->where('id', 'LIKE', "%{$search}%")
                    ->orWhere('code', 'LIKE', "%{$search}%")
                    ->orWhere('tanggal_mulai', 'LIKE', "%{$tanggal}%");
                })
                // Tambahkan kondisi untuk hanya mengambil data dengan relasi `getTestByPelatihanAndPengembangan` yang tidak kosong
                ->whereHas('getTestByPelatihanAndPengembangan');

            $totalFiltered = $query->count();

            // Ambil data dengan batas dan urutan
            $posts = $query->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            // Format hasil data
            if (!empty($posts)) {
                $no = $start + 1;
                foreach ($posts as $a) {
                    $d = $a;
                    $data[] = $d;
                    $d['pelatihan'] = $a->aksesPelatihan ? $a->aksesPelatihan : '-';
                    $d['vendor'] = $a->getVendor ? $a->getVendor : '-';
                    $d['test'] = $a->getTestByPelatihanAndPengembangan ? $a->getTestByPelatihanAndPengembangan : '-';
                }
            }

            $json_data = [
                "draw" => intval($request->input('draw')),
                "recordsTotal" => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data" => $data
            ];

            return response()->json($json_data);

        } catch (Exception $e) {
            return ____error([__FUNCTION__, 'Line : '.$e->getLine(). ' Message : '.$e->getMessage()]);
        }
    }

}

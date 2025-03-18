<?php

namespace App\Http\Controllers\Employe;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Auth;

use App\CompanyEvaluasiModel;
use App\CompanyEvaluasiPenilaianModel;

class EvaluasiLevel3UserController extends Controller
{
    function index(){
        return view('pages.employe.evaluasi_3.index');
    }

    function detail($id){
        // return CompanyEvaluasiModel::where('id', $id)->with(['user', 'getEvaluasi3.pelatihan','getEvaluasi3.evaluasiDetail'])->first();
        return view('pages.employe.evaluasi_3.detail', [
            'getData' => CompanyEvaluasiModel::where('id', $id)->with(['user', 'getEvaluasi3.pelatihan', 'getEvaluasi3.evaluasiDetail'])->first()
        ]);
    }

    public function dataTable(Request $request)
    {
        try {

            $columns = [null, 'email', 'employe_name'];
            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')] ?? 'id';
            $dir = $request->input('order.0.dir') ?? 'DESC';

            $data = [];
            $totalData = CompanyEvaluasiModel::where('user_id', Auth::user()->id)->with(['user', 'getEvaluasi3.pelatihan'])->count();
            $totalFiltered = $totalData;
            $posts = '';

            if (empty($request->input('search.value'))) {
                $query = CompanyEvaluasiModel::where('user_id', Auth::user()->id)
                    ->with(['user', 'getEvaluasi3.pelatihan']);

                $searchPelatihan = $request->input('pelatihan');
                if (!empty($searchPelatihan) && $searchPelatihan != 'all') {
                    $query->whereHas('getEvaluasi3.pelatihan', function ($q) use ($searchPelatihan) {
                        $q->where('kategori', $searchPelatihan);
                    });
                }

                $searchStatus = $request->input('status');
                if (!empty($searchStatus) && $searchStatus != 'all') {
                    $searchStatus = $request->input('status');
                    $query->where('status', $searchStatus);
                }

                $posts = $query->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            } else {

                $search = $request->input('search.value');
                $searchPelatihan = $request->input('pelatihan');
                $searchStatus = $request->input('status');

                $tb = CompanyEvaluasiModel::where('user_id', Auth::user()->id)
                    ->with(['user', 'getEvaluasi3.pelatihan']);

                    // Search by `judul_pelatihan` in `getEvaluasi3.pelatihan`
                if (!empty($search)) {
                    $tb->where(function ($query) use ($search) {
                        $query->where('id', 'LIKE', "%{$search}%")
                            ->orWhereHas('getEvaluasi3.pelatihan', function ($q) use ($search) {
                                $q->where('judul_pelatihan', 'LIKE', "%{$search}%");
                            });
                    });
                }

                // Filter by `kategori` in `getEvaluasi3.pelatihan`
                if (!empty($searchPelatihan) && $searchPelatihan != 'all') {
                    $tb->whereHas('getEvaluasi3.pelatihan', function ($q) use ($searchPelatihan) {
                        $q->where('kategori', $searchPelatihan);
                    });
                }

                // Filter by `status` in the main model
                if (!empty($searchStatus)) {
                    $tb->where('status', $searchStatus);
                }
                $totalFiltered = $tb->count();

                $posts = $tb->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();




            }

            // dd($posts);

            if (!empty($posts)) {

                $no = $start + 1;

                foreach ($posts as $a) {
                    // dd($a);
                    $d = $a;

                    $data[] = $d;
                }
            }
            // dd('asdasd = ', $data);
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

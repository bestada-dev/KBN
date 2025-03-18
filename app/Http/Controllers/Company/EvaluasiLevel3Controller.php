<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Auth;

use App\CompanyEvaluasiModel;
use App\CompanyEvaluasiPenilaianModel;

class EvaluasiLevel3Controller extends Controller
{
    function index(){
        return view('pages.company.evaluasi_3.index');
    }

    function detail($id){
        // return CompanyEvaluasiModel::where('id', $id)->with(['user', 'getEvaluasi3.pelatihan','getEvaluasi3.evaluasiDetail'])->first();
        return view('pages.company.evaluasi_3.detail', [
            'getData' => CompanyEvaluasiModel::where('id', $id)->with(['user', 'getEvaluasi3.pelatihan', 'getEvaluasi3.evaluasiDetail'])->first()
        ]);
    }

    function detailAfterEvaluasi($id){
        // return CompanyEvaluasiModel::where('id', $id)->with(['user', 'getEvaluasi3.pelatihan', 'getEvaluasiPenilaian'])->first();
        return view('pages.company.evaluasi_3.detail_after_evaluasi', [
            'getData' => CompanyEvaluasiModel::where('id', $id)->with(['user', 'getEvaluasi3.pelatihan', 'getEvaluasiPenilaian'])->first()
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
            $totalData = CompanyEvaluasiModel::where('company_id', Auth::user()->id)->with(['user', 'getEvaluasi3.pelatihan'])->count();
            $totalFiltered = $totalData;
            $posts = '';

            if (empty($request->input('search.value'))) {
                $posts = CompanyEvaluasiModel::where('company_id', Auth::user()->id)->with(['user', 'getEvaluasi3.pelatihan'])->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            } else {

                $search = $request->input('search.value');
                $tb = CompanyEvaluasiModel::where('company_id', Auth::user()->id)->with(['user', 'getEvaluasi3.pelatihan'])
                    ->where('id', 'LIKE', "%{$search}%");

                $posts = $tb->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = $tb->count();
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

    public function storePenilaian(Request $request)
    {
        $request->validate([
            'company_evaluasi_id' => 'required|integer',
            'pertanyaan.*' => 'required|string',
            'kategori.*' => 'required|string',
            'nilai.*' => 'required|integer',
        ]);

        $companyEvaluasiId = $request->input('company_evaluasi_id');
        $pertanyaans = $request->input('pertanyaan');
        $kategoris = $request->input('kategori');
        $nilais = $request->input('nilai');
        $catatans = $request->input('catatan', []);

        // Loop untuk menyimpan setiap penilaian ke CompanyEvaluasiPenilaianModel
        foreach ($pertanyaans as $index => $pertanyaan) {
            CompanyEvaluasiPenilaianModel::create([
                'company_evaluasi_id' => $companyEvaluasiId,
                'pertanyaan' => $pertanyaan,
                'kategori' => $kategoris[$index],
                'nilai' => $nilais[$index],
                'catatan' => $catatans[$index] ?? null,
            ]);
        }

        // Periksa dan simpan catatan tambahan jika ada
        $companyEvaluasi = CompanyEvaluasiModel::find($companyEvaluasiId);
        if ($companyEvaluasi) {
            if ($request->filled('catatan_evaluasi')) {
                $companyEvaluasi->catatan = $request->input('catatan_evaluasi');
            }

            // Update status is_finish dan tanggal_evaluasi
            $companyEvaluasi->is_finish = 1;
            $companyEvaluasi->status = "Sudah Dievaluasi";
            $companyEvaluasi->tanggal_evaluasi = Carbon::now();

            // Simpan perubahan ke model CompanyEvaluasi
            $companyEvaluasi->save();
        }

        return response()->json(['success' => true]);
    }
}

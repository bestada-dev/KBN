<?php

namespace App\Http\Controllers\Employe;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\EvaluasiLevel1Model;
use App\EmployeTestModel;
use App\EvaluasiLevel1PenilaianModel;
use Auth;

class EvaluasiLevel1Controller extends Controller
{
    function index(){
        return view('pages.employe.evaluasi_1.index');
    }

    function detail($id){
        // return EmployeTestModel::where('id', $id)->with(['user', 'getEvaluasi3.pelatihan','getEvaluasi3.evaluasiDetail'])->first();

        // dd(EmployeTestModel::with(['getPelatihan.getVendor', 'user',])->find($id));
        $data = EmployeTestModel::with(['getPelatihan.getVendor', 'user',])
        ->where('id',$id)->first();
        $pertanyaan = DB::table('pertanyaan_evaluasi_level1')->get();

        // return json_encode($pertanyaan);
        return view('pages.employe.evaluasi_1.detail', [
            // OLD 'getData' => EvaluasiLevel1Model::where('employe_test_id', $id)->with(['getEmployeTest.getPelatihan.getVendor', 'user', 'company', 'getEvaluasiPenilaian'])->first()
            'getData' => $data,
            'getPertanyaan' => $pertanyaan,
            'id' => $id
        ]);
    }

    function detailAfterEvaluasi($id){
        // return EmployeTestModel::where('id', $id)->with(['user', 'getEvaluasi3.pelatihan', 'getEvaluasiPenilaian'])->first();
        $data = EmployeTestModel::with(['getEvaluasiLv1.getEvaluasiPenilaian','getPelatihan.getVendor', 'user',])
        ->where('id',$id)->first();
       
        return view('pages.employe.evaluasi_1.detail_after_evaluasi', [
            'getData' => $data,
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


            $kategori_pelatihan = $request->input('pelatihan_id'); // Get the type filter value
            $status_evaluasi = $request->input('status_evaluasi'); 

            $data = [];
            $totalData = EmployeTestModel::where('user_id', Auth::user()->id)->with(['user', 'getEvaluasiLv1'])->count();
            $totalFiltered = $totalData;
            $posts = '';

            $query = EmployeTestModel::where('user_id', Auth::user()->id)->where('status', 'Selesai')->with(['user', 'getEvaluasiLv1', 'getPelatihan']);

            $search = $request->input('search.value');

            if (!empty($search)) {
                $query->where(function ($q) use ($search, $kategori_pelatihan, $judul_pelatihan_id) {
                    $q
                    // ->where('id', 'LIKE', "%{$search}%")
                    //   ->orWhere('certificate_number', 'LIKE', "%{$search}%")
                    //   ->whereHas('user.company', function ($q) use ($search) {
                    //         $q->where('admin_name', 'LIKE', "%{$search}%");
                    //     });
                    ->whereHas('getPelatihan.getVendor', function ($q) use ($search) {
                        $q->where('admin_name', 'LIKE', "%{$search}%");
                    })
                      ->orWhereHas('getPelatihan', function ($q) use ($search) {
                          $q->where('judul_pelatihan', 'LIKE', "%{$search}%");
                      });
                });

            }
            
            if (!empty($kategori_pelatihan)) {
                $query->where(function ($q) use ($search, $kategori_pelatihan) {
                    $q->orWhereHas('getPelatihan', function ($q) use ($kategori_pelatihan) {
                        $q->where('kategori', 'LIKE', "%{$kategori_pelatihan}%");
                    });
                });
            }

            if (!empty($status_evaluasi)) {
                if ($status_evaluasi === 'Belum Dievaluasi') {
                    $query->whereDoesntHave('getEvaluasiLv1'); // Equivalent to whereNotHas
                } else {
                    $query->whereHas('getEvaluasiLv1', function ($q) use ($status_evaluasi) {
                        $q->where('status', $status_evaluasi);
                    });
                }
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

            return ____error([__FUNCTION__, 'Line : '.$e->getLine(). ' Message : '.$e->getMessage()]);

        }
    }

    // OLD
    // public function storePenilaian(Request $request)
    // {

    //     try {
    //     $request->validate([
    //         'employe_test_id' => 'required|integer',
    //         'pertanyaan.*' => 'required|string',
    //         'kategori.*' => 'required|string',
    //         'nilai.*' => 'required|integer',
    //     ]);

    //     $employeTestId = $request->input('employe_test_id');
        
    //     $empTest = EmployeTestModel::find($employeTestId);

    //     $evaluasiLv1 = EvaluasiLevel1Model::updateOrCreate([
    //         'tanggal_evaluasi' => now(),
    //         'status' => 'Sudah Dievaluasi',
    //         'catatan' => $request->catatan_evaluasi,
    //         'user_id' => $empTest->user_id,
    //         'company_id' => $empTest->created_by,
    //     ], [
    //         'employe_test_id' => $employeTestId
    //     ]);

    //     $pertanyaans = $request->input('question');
    //     $subtitles = $request->input('subtitle');
    //     $titles = $request->input('title');
    //     $isQuestionDisplayeds = $request->input('isQuestionDisplayed');
    //     $isSubtitleDisplayed = $request->input('isSubtitleDisplayed');
    //     $kategoris = $request->input('kategori');
    //     $nilais = $request->input('nilai');
    //     $catatans = $request->input('catatan', []);

    //     // Loop untuk menyimpan setiap penilaian ke CompanyEvaluasiPenilaianModel
    //     foreach ($pertanyaans as $index => $pertanyaan) {
    //         EvaluasiLevel1PenilaianModel::create([
    //             'evaluasI_level1_id' => $evaluasiLv1->id,
    //             'title' => $titles[$index],
    //             'subtitle' => $subtitles[$index],
    //             'isQuestionDisplayed' => $isQuestionDisplayed[$index],
    //             'isSubtitleDisplayed' => $isSubtitleDisplayed[$index],
    //             'question' => $pertanyaan,
    //             'kategori' => $kategoris[$index],
    //             'nilai' => $nilais[$index],
    //             'catatan' => $catatans[$index] ?? null,
    //         ]);
    //     }


    //     EvaluasiLevel1PenilaianModel::create([
    //         'evaluasI_level1_id' => $evaluasiLv1->id,
    //         'question' => $pertanyaan,
    //         'kategori' => $kategoris[$index],
    //         'nilai' => $nilais[$index],
    //         'catatan' => $catatans[$index] ?? null,
    //     ]);

    //     // Periksa dan simpan catatan tambahan jika ada
    //     $companyEvaluasi = CompanyEvaluasiModel::find($companyEvaluasiId);
    //     if ($companyEvaluasi) {
    //         if ($request->filled('catatan_evaluasi')) {
    //             $companyEvaluasi->catatan = $request->input('catatan_evaluasi');
    //         }

    //         // Update status is_finish dan tanggal_evaluasi
    //         $companyEvaluasi->is_finish = 1;
    //         $companyEvaluasi->status = "Sudah Dievaluasi";
    //         $companyEvaluasi->tanggal_evaluasi = Carbon::now();

    //         // Simpan perubahan ke model CompanyEvaluasi
    //         $companyEvaluasi->save();
    //     }

    //     return response()->json(['success' => true]);
    // } catch (Exception $e) {
    //     return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
    // }
    // }

    public function storePenilaian(Request $request)
{
    try {
        $request->headers->set('Accept', 'application/json');

        $request->validate([
            'employe_test_id' => 'required|integer',
            'question.*' => 'required|string',
            'kategori.*' => 'required|string',
            // 'nilai.*' => 'required|integer',
            'title.*' => 'nullable|string',
            'subtitle.*' => 'nullable|string',
            'isQuestionDisplayed.*' => 'required|boolean',
            'isSubtitleDisplayed.*' => 'required|boolean',
        ]);

        $employeTestId = $request->input('employe_test_id');
        $empTest = EmployeTestModel::findOrFail($employeTestId);

        $evaluasiLv1 = EvaluasiLevel1Model::updateOrCreate([
            'employe_test_id' => $employeTestId,
        ], [
            'tanggal_evaluasi' => now(),
            'status' => 'Sudah Dievaluasi',
            'catatan' => $request->catatan_evaluasi,
            'user_id' => $empTest->user_id,
            'company_id' => $empTest->created_by,
        ]);

        $pertanyaans = $request->input('question');
        $subtitles = $request->input('subtitle');
        $titles = $request->input('title');
        $isQuestionDisplayeds = $request->input('isQuestionDisplayed');
        $isSubtitleDisplayed = $request->input('isSubtitleDisplayed');
        $kategoris = $request->input('kategori');
        $nilais = $request->input('nilai');
        $catatans = $request->input('catatan', []);
        EvaluasiLevel1PenilaianModel::query()->delete();
        foreach ($pertanyaans as $index => $pertanyaan) {
            EvaluasiLevel1PenilaianModel::create([
                'evaluasi_level1_id' => $evaluasiLv1->id,
                'title' => $titles[$index] ?? null,
                'subtitle' => $subtitles[$index] ?? null,
                'isQuestionDisplayed' => $isQuestionDisplayeds[$index] ?? true,
                'isSubtitleDisplayed' => $isSubtitleDisplayed[$index] ?? false,
                'question' => $pertanyaan,
                'kategori' => $kategoris[$index] ?? '-',
                'nilai' => $nilais[$index] ?? '-',
                'catatan' => $catatans[$index] ?? null,
            ]);
        }

        return response()->json(['success' => true]);
    } catch (Exception $e) {
        Log::error('Error in storePenilaian: ' . $e->getMessage());
        return response()->json(['error' => 'An error occurred. Please check the logs.'], 500);
    }
}

}

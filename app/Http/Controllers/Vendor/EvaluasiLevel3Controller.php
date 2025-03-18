<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\PelatihanSayaModel;
use App\EvaluasiLevel3Model;
use App\EvaluasiLevel3DetailModel;
use App\AksesPelatihanSettingModel;
use App\CompanyEvaluasiModel;
use App\EmployeTestModel;
use App\SettingTimeEvaluasiModel;

class EvaluasiLevel3Controller extends Controller
{
    function index(){
        return view('pages.vendor.evaluasi_level_3.index');
    }

    function create(){
        return view('pages.vendor.evaluasi_level_3.create');
    }

    function detail($id){
        // return EvaluasiLevel3Model::with(['pelatihan', 'evaluasiDetail'])->where('id', $id)->first();
        return view('pages.vendor.evaluasi_level_3.detail', [
            'data' => EvaluasiLevel3Model::with(['pelatihan', 'evaluasiDetail'])->where('id', $id)->first()
        ]);
    }

    function update($id){
        // return EvaluasiLevel3Model::with(['pelatihan', 'question.answer'])->where('id', $id)->first();
        return view('pages.vendor.evaluasi_level_3.update', [
            'judulPelatihanList' => PelatihanSayaModel::get(),
            'edit_data' => EvaluasiLevel3Model::with('evaluasiDetail')->where('id', $id)->first()
        ]);
    }

    public function getJudulPelatihan(Request $request)
    {
        $pelatihan = $request->input('pelatihan');

        $judulPelatihan = PelatihanSayaModel::where('kategori', $pelatihan)->get();

        // Mengembalikan respons sebagai JSON
        return response()->json($judulPelatihan);
    }


    public function dataTable(Request $request)
    {
        try {
            $columns = [null, 'code', 'judul_pelatihan_id'];
            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')] ?? 'id';
            $dir = $request->input('order.0.dir') ?? 'DESC';

            $data = [];
            $totalData = EvaluasiLevel3Model::with('pelatihan')->count();
            $totalFiltered = $totalData;

            // Ambil nilai filter dari request
            $search = $request->input('search.value');
            $pelatihan = $request->input('pelatihan');
            $typeTest = $request->input('type_test');

            // Filter query utama
            $query = EvaluasiLevel3Model::with('pelatihan')->when($search, function ($q) use ($search) {
                    $q->where('id', 'LIKE', "%{$search}%")
                    ->orWhere('code', 'LIKE', "%{$search}%")
                    ->orWhereHas('pelatihan', function ($q2) use ($search) {
                        $q2->where('judul_pelatihan', 'LIKE', "%{$search}%");
                    });
                })
                ->when($pelatihan, function ($q) use ($pelatihan) {
                    $q->whereHas('pelatihan', function ($q2) use ($pelatihan) {
                        $q2->where('kategori', 'LIKE', "%{$pelatihan}%"); // Gunakan LIKE agar lebih fleksibel
                    });
                });

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


    public function store(Request $request)
    {
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'tipe_pelatihan' => 'required|string',
            'judul_pelatihan_id' => 'required|string',
            'pertanyaan.*' => 'required|string',
        ]);


        $getSetingTanggalPelaksanaan = SettingTimeEvaluasiModel::first();
        $pelatihanSaya = PelatihanSayaModel::where('id', $request->judul_pelatihan_id)->first();

        if ($getSetingTanggalPelaksanaan && $pelatihanSaya) {
            // Parse the date from $pelatihanSaya->tanggal_akhir to a Carbon instance
            $tanggalAkhir = Carbon::parse($pelatihanSaya->tanggal_akhir);

            // Check the type and apply the corresponding date addition based on `total`
            switch (strtolower($getSetingTanggalPelaksanaan->type)) {
                case 'hari':
                    $tanggalAkhir->addDays($getSetingTanggalPelaksanaan->total);
                    break;
                case 'bulan':
                    $tanggalAkhir->addMonths($getSetingTanggalPelaksanaan->total);
                    break;
                case 'tahun':
                    $tanggalAkhir->addYears($getSetingTanggalPelaksanaan->total);
                    break;
                default:
                    return response()->json(['error' => 'Invalid type in setting.'], 400);
            }
        }

        // return $tanggalAkhir->toDateString();

        try {
            DB::beginTransaction();

            $lastEvaluasi = EvaluasiLevel3Model::orderBy('code', 'desc')->first();

            if ($lastEvaluasi) {
                $lastEvaluasiCode = (int)substr($lastEvaluasi->code, -4);
                $nextSequenceNumber = $lastEvaluasiCode + 1;
            } else {
                $nextSequenceNumber = 1;
            }

            // Pad the sequence number to ensure it has 4 digits
            $evaluasiCode = str_pad($nextSequenceNumber, 4, '0', STR_PAD_LEFT);

            $cekCompanyAndEmployeByPelatihan = EmployeTestModel::where('judul_pelatihan_id', $request->judul_pelatihan_id)->get();

            $evaluasiCreate = EvaluasiLevel3Model::create([
                'code' => $evaluasiCode,
                'tipe_pelatihan' => $request->tipe_pelatihan,
                'judul_pelatihan_id' => $request->judul_pelatihan_id,
                'tanggal_pelaksanaan' => $tanggalAkhir->toDateString(),
                'status' => 1,
                'status_admin' => 1,
            ]);

            foreach ($cekCompanyAndEmployeByPelatihan as $company) {
                CompanyEvaluasiModel::create([
                    'evaluasi_level_3_id' => $evaluasiCreate->id,
                    'user_id' => $company->user_id,
                    'company_id' => $company->created_by,
                    'status' => 'Belum Dievaluasi',
                    'catatan' => null,
                    'tanggal_evaluasi' => null,
                    'is_finish' => 0,
                ]);
            }

            if (!empty($request->pertanyaan)) {
                foreach ($request->pertanyaan as $pertanyaan) {
                    EvaluasiLevel3DetailModel::create([
                        'evaluasi_level_3_id' => $evaluasiCreate->id,
                        'pertanyaan' => $pertanyaan,
                    ]);
                }
            }

            DB::commit(); // Commit the transaction

            return response()->json(['message' => 'Data Berhasil Disimpan'], 200);
        } catch (\Throwable $th) {
            DB::rollBack(); // Rollback transaction on error


            Log::error('Error saving evaluasi data: ' . $th->getMessage());
            return response()->json(['error' => 'Data Gagal Disimpan: ' . $th->getMessage()], 500);
        }
    }

    public function edit(Request $request, $id)
    {
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'tipe_pelatihan' => 'required|string',
            'judul_pelatihan_id' => 'required|string',
            'pertanyaan.*' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $evaluasi = EvaluasiLevel3Model::findOrFail($id);

            $evaluasi->tipe_pelatihan = $request->tipe_pelatihan;
            $evaluasi->judul_pelatihan_id = $request->judul_pelatihan_id;
            $evaluasi->save();

            $cekCompanyandEmployeByPelatihan = EmployeTestModel::where('judul_pelatihan_id', $request->judul_pelatihan_id)->get();

            // Check for existing company records to prevent duplicates
            foreach ($cekCompanyandEmployeByPelatihan as $company) {
                $existingEmployeandCompanyEvaluasi = CompanyEvaluasiModel::where([
                    'evaluasi_level_3_id' => $evaluasi->id,
                    'user_id' => $company->user_id,
                    'company_id' => $company->created_by,
                ])->first();

                // Only create a new record if it doesn't exist
                if (!$existingEmployeandCompanyEvaluasi) {
                    CompanyEvaluasiModel::create([
                        'evaluasi_level_3_id' => $evaluasi->id,
                        'user_id' => $company->user_id,
                        'company_id' => $company->created_by,
                        'status' => 'Belum Dievaluasi',
                        'catatan' => null,
                        'tanggal_evaluasi' => null,
                        'is_finish' => 0,
                    ]);
                }
            }

            EvaluasiLevel3DetailModel::where('evaluasi_level_3_id', $evaluasi->id)->delete();
            foreach ($request->pertanyaan as $pertanyaan) {
                EvaluasiLevel3DetailModel::create([
                    'evaluasi_level_3_id' => $evaluasi->id,
                    'pertanyaan' => $pertanyaan,
                ]);
            }

            DB::commit();

            return response()->json(['message' => 'Data Berhasil Diperbarui'], 200);

        } catch (\Exception $e) {
            DB::rollBack(); // Roll back the transaction if there's an error
            Log::error('Error updating evaluasi data: ' . $e->getMessage());

            return response()->json(['error' => 'Data gagal diperbarui: ' . $e->getMessage()], 500);
        }
    }

    // Fungsi untuk menghapus user (Delete)
    public function delete(Request $request)
    {
        try {
            // Retrieve the test IDs from the request
            $testIds = $request->ids;
            $pushTestId = [];
            $getTest = EvaluasiLevel3Model::where('id', $testIds)->get();
            for ($i=0; $i < count($getTest) ; $i++) {
                array_push($pushTestId, $getTest[$i]['id']);
            }



            CompanyEvaluasiModel::whereIn('evaluasi_level_3_id', $pushTestId)->delete();
            EvaluasiLevel3DetailModel::whereIn('evaluasi_level_3_id', $pushTestId)->delete();
            EvaluasiLevel3Model::whereIn('id', $testIds)->delete();


            return response()->json(['status' => true, 'message' => 'Data berhasil dihapus.']);
        } catch (Exception $e) {
            \Log::error('Error deleting tests: ' . $e->getMessage());
            return response()->json(['error' =>  $e->getMessage()], 500);
        }
    }
}

<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Auth;
use DB;
use Exception; // Import the Exception class

use App\AksesPelatihanSettingModel; // Import Answer model
use App\PelatihanSayaModel;
use App\CompanyModel;
use App\Users;
use App\EmployeTestModel;
use App\EmployeTestDetailModel;

class TestSettingController extends Controller
{
    function index(){
        return view('pages.company.test_setting.index', [
            'get_perusahaan' => CompanyModel::get()
        ]);
    }

    function detail($id){
        // return AksesPelatihanSettingModel::with(['getPelatihanDanPengembangan.getVendor'])->where('id', $id)->first();
        return view('pages.company.test_setting.detail', [
            'get_perusahaan' => AksesPelatihanSettingModel::with(['getPelatihanDanPengembangan.getVendor'])->where('id', $id)->first(),
            'get_employe' => Users::where('role_id', 3)->where('company_id', Auth::user()->company_id)->get(),
            'get_test_user' => EmployeTestModel::with('user')->where('seting_id', $id)->get(),
            'no' => 1
        ]);
    }

    public function store(Request $request)
    {
        // return $request->all();
        // Validasi data input
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|max:255',
            'user_id' => 'required|array',
            'user_id.*' => 'exists:users,id', // Pastikan ID user valid
            'judul_pelatihan_id' => 'required|integer',
            'nilai' => 'required|numeric',
            'created_by' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        try {

            $employeTestIds = []; // Menyimpan ID setiap employe_test
        foreach ($request->user_id as $userId) {
            // Menyimpan data ke dalam tabel EmployeTestModel
            $employeTest = EmployeTestModel::create([
                'seting_id' => $request->seting_id,
                'type' => $request->type,
                'user_id' => $userId,
                'judul_pelatihan_id' => $request->judul_pelatihan_id,
                'nilai_pre_test' => 0,
                'nilai_post_test' => 0,
                'status' => 'Belum Pre Test', // Status awal
                'created_by' => $request->created_by
            ]);

            // Simpan ID employe_test yang baru saja dibuat
            $employeTestIds[] = $employeTest->id;
        }

        // Ambil pelatihan berdasarkan judul_pelatihan_id
        $findPelatihan = PelatihanSayaModel::with('getTestByPelatihanAndPengembangan')
            ->where('id', $request->judul_pelatihan_id)
            ->first();

        if (count($findPelatihan['getTestByPelatihanAndPengembangan']) > 0) {
            $tmp = [];
            // Loop untuk mempersiapkan data terkait test
            for ($i = 0; $i < count($findPelatihan['getTestByPelatihanAndPengembangan']); $i++) {
                foreach ($employeTestIds as $employeTestId) {
                    // Push data ke array tmp
                    array_push($tmp, [
                        'employe_test_id' => $employeTestId, // Menggunakan ID yang baru saja dibuat
                        'type' => $findPelatihan['kategori'],
                        'judul_pelatihan_id' => $findPelatihan['id'],
                        'test_id' => $findPelatihan['getTestByPelatihanAndPengembangan'][$i]['id'],
                        'nilai' => 0,
                        'is_finish' => 0, // 0 berarti belum selesai
                    ]);
                }
            }

            // Simpan data tmp ke tabel yang sesuai (misalnya, test_details)
            // Pastikan ada model dan tabel untuk menyimpan data tmp ini
            EmployeTestDetailModel::insert($tmp);
        }

        return response()->json(['success' => true, 'message' => 'Data berhasil disimpan']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan data', 'error' => $e->getMessage()]);
        }
    }

    function delete($id) {
        try {
            $findData = EmployeTestModel::where('id', $id)->first();

            if(!empty($findData)){
                $findData->delete();
                return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);
            }else{
                return response()->json(['success' => false, 'message' => 'Data tidak ditemukan']);
            }
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan data', 'error' => $e->getMessage()]);
        }
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
            $totalData = AksesPelatihanSettingModel::with(['getPelatihanDanPengembangan.getVendor'])->where('company_id', Auth::user()->company_id)->count();
            $totalFiltered = $totalData;

            // Ambil nilai filter dari request
            $search = $request->input('search.value');
            $pelatihan = $request->input('pelatihan');

            // Filter query utama
            $query = AksesPelatihanSettingModel::with(['getPelatihanDanPengembangan.getVendor'])->where('company_id', Auth::user()->company_id)->when($search, function ($q) use ($search) {
                    $q->where('id', 'LIKE', "%{$search}%")
                    ->orWhere('code', 'LIKE', "%{$search}%")
                    ->orWhere('type', 'LIKE', "%{$pelatihan}%");
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
}
